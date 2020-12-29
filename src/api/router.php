<?php
namespace api;

class router
{   
    public static function map($method, $controller, $uri)
    {
        $endpoint_config = \api\endpoints::get_config();
        $endpoints = \api\endpoints::parse_config($endpoint_config);
        return static::find_endpoint($endpoints, $uri);     
    }
    
    public static function find_endpoint($endpoints, $uri)
    {
        // Accidentaly built in sanitation through api endpoint parsing/definition
        foreach ($endpoints as $endpoint => $pattern)
            if (preg_match($pattern, $uri) === 1)
                return $endpoint;
        throw new \Exception('Bad request, endpoint does not exist', 400);
    }    
}
