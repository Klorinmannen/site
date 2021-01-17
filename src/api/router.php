<?php
namespace api;

class router
{
    private $_config = null;
    private $_request = null;

    private $_controller = null;
    private $_params = null;
    private $_method = null;
    
    public function __construct($request)
    {
        $this->_config = new \api\config();
        $this->_request = $request;
    }

    public function map()
    {
        $routes = self::get_routes();
        $uri = $this->_request->get_uri();
        $parameters = [];
        $method = '';
        foreach ($routes as $route_pattern => $route_method) {
            preg_match($route_pattern, $uri, $params);
            if (isset($params[0])) {

                // The first match is the matched uri itself
                array_shift($params);

                $parameters = $params;
                $method = $route_method;
                
                break;
            }
        }

        self::set_controller($uri, $parameters);
        $this->_params = $parameters;
        $this->_method = $method;

        if (!$this->_method)
            throw new \Exception('Endpoint not found', 400);
    }

    private function set_controller($uri, $params)
    {
        $controller = $uri;
        if (isset($params[0]))
            $controller = substr($uri, 0, strpos($uri, $params[0]));

        $this->_controller = str_replace('/', '\\', trim($controller, '/')).'\\api\\controller';
    }

    private function get_routes()
    {
        $method = $this->_request->get_method();
        $routes = $this->_config->get_routes();

        $method = strtolower($method);
        if (! isset($routes[$method]))
            throw new \Exception('No endpoint present with given method error', 400);        
        
        return self::swap_in_route_patterns($routes[$method]);
    }

    private function swap_in_route_patterns($routes)
    {
        $pattern_routes = [];
        foreach ($routes as $route => $method) {
            $route_pattern = self::create_pattern_from_route($route);
            $pattern_routes[$route_pattern] = $method;
        }
        return $pattern_routes;
    }

    private function create_pattern_from_route($route)
    {
        // The order is important
        $route_pattern = preg_replace('/{[^}]+}/', '(\w+)', $route);
        $route_pattern = preg_replace('/\//', '\/', $route_pattern);
        $route_pattern = sprintf('/^%s$/', $route_pattern);        
        return $route_pattern;
    }
    
    public function get_params() { return $this->_params; }
    public function get_method() { return $this->_method; }
    public function get_controller() { return $this->_controller; }
    public function get_routing_info() { return [ 'controller' => $this->_controller, 'method' => $this->_method, 'params' => $this->_params ]; }
}
