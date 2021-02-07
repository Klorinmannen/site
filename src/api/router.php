<?php
namespace api;

class router
{
    private $_config = null;
    private $_request = null;
    private $_controller = null;
    private $_params = null;
    private $_endpoint = null;
    
    public const CONTROLLER_PATH = '\\api\\controller';
    public const PARAM_PATTERNS = [ '/{id}/' => '([0-9]+)',
                                    '/{id\/name}/' => '([a-zA-Z0-9]+)',
                                    '/{name}/' => '([a-zA-Z]+)',
                                    '/{state}/' => '([a-zA-Z]+)' ];
    
    public function __construct($request)
    {
        $this->_config = new \api\config();
        $this->_request = $request;
    }
    
    public function map()
    {
        $routes = self::get_routes();
        $uri = $this->_request->get_uri();

        // Match request-path against defined path patterns endpoints
        if (!$matched = self::match_path_route($routes, $uri))
            throw new \Exception('Endpoint not found', 400);

        if ($matched['security'])
            if (!self::validate_security())
                throw new \Exception('Authorization error', 401);
        
        // The first match is the matched path itself
        array_shift($matched['parameters']);

        $resource = self::prepare_resource($matched['resource']);
        
        $this->_controller = self::create_controller($resource);
        $this->_params = $matched['parameters'];
        $this->_endpoint = $matched['endpoint'];
    }

    private function validate_security()
    {
        $jwt = $this->_request->get_jwt();
        if ($jwt === null)
            return false;
        if (! \util\jwt::validate($jwt))
            return false;
        return true;
    }
        
    private function match_path_route($routes, $uri)
    {
        foreach ($routes as $route_pattern => $route)        
            if (preg_match($route_pattern, $uri, $parameters) === 1)
                return [ 'parameters' => $parameters,
                         'endpoint' => $route['endpoint'],
                         'resource' => $route['resource'],
                         'security' => $route['security'] ];
        return false;
    }

    private function prepare_resource($resource)
    {
        $resource = trim($resource, '/');
        $resource = str_replace('/', '\\', $resource);
        return $resource;
    }
    
    private function create_controller($resource)
    {
        return sprintf('%s%s', $resource, static::CONTROLLER_PATH);
    }

    private function get_routes()
    {
        $method = $this->_request->get_method();
        $routes = $this->_config->get_routes();

        $method = strtolower($method);
        if (! isset($routes[$method]))
            throw new \Exception('No endpoints found with the used method', 400);        

        $routes = $routes[$method]; 
        ksort($routes);

        return self::swap_in_route_patterns($routes);
    }

    private function swap_in_route_patterns($routes)
    {
        $route_patterns = [];
        foreach ($routes as $route => $route_endpoint) {
            $route_pattern = self::create_pattern_from_route($route);
            $route_patterns[$route_pattern] = $route_endpoint;
        }
        return $route_patterns;
    }

    private function create_pattern_from_route($route)
    {
        $route_pattern = $route;
        foreach (static::PARAM_PATTERNS as $param => $pattern)
            $route_pattern = preg_replace($param, $pattern, $route_pattern);

        $route_pattern = preg_replace('/\//', '\/', $route_pattern);
        $route_pattern = sprintf('/^%s$/', $route_pattern);        
        return $route_pattern;
    }
    
    public function get_params() { return $this->_params; }
    public function get_method() { return $this->_endpoint; }
    public function get_controller() { return $this->_controller; }
    public function get_routing_info() { return [ 'controller' => $this->_controller, 'method' => $this->_endpoint, 'params' => $this->_params ]; }
}
