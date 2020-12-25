<?php
namespace api;

class router
{   
    public static function map($method, $controller, $uri)
    {
        static::validate_controller($controller);

        $endpoint_config = \api\endpoints::get_config();
        if (! isset($endpoint_config[$controller]))
            throw new \Exception('Bad request, endpoint does not exist', 400);            
        if (! isset($endpoint_config[$controller][$method]))
            throw new \Exception('Bad request, endpoint does not exist', 400);            

        $endpoints = \api\endpoints::parse_config($endpoint_config[$controller][$method]);
        return static::find_endpoint($endpoints, $uri);     
    }

    public static function validate_controller($controller)
    {
        $api_controller = sprintf('%s\api\controller', $controller);
        if (!class_exists($api_controller))
            throw new \Exception('Bad request, endpoint does not exist', 400);
        return true;
    }
    
    public static function find_endpoint($endpoints, $uri)
    {
        foreach ($endpoints as $endpoint => $pattern)
            if (preg_match($pattern, $uri) === 1)
                return $endpoint;
        throw new \Exception('Bad request, endpoint does not exist', 400);
    }    
}
