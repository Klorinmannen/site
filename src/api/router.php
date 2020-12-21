<?php
namespace api;

class router
{
    public static function map($method, $controller, $uri)
    {
        $method_endpoints = static::get_endpoints($controller);
        $endpoints = \api\endpoints::parse($method_endpoints[$method]);
        return static::find_endpoint($endpoints, $uri);        
    }

    public static function find_endpoint($endpoints, $uri)
    {
        foreach ($endpoints as $endpoint => $pattern) {
            if (preg_match($pattern, $uri) === 1)
                return $endpoint;
        }
        return false;
    }
    
    public static function get_endpoints($controller)
    {
        $api = sprintf('%s\api', $controller);
        return $api::get_endpoints();
    }
    
}
