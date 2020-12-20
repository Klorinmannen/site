<?php
namespace api;

class request
{
    public static function parse()
    {
        $uri = static::get_uri();
        echo $uri;
        $path = static::get_config($uri);
        $endpoints = static::get_endpoints($path);

        $method = $_SERVER['REQUEST_METHOD'];
        $methods = $endpoints[$method];
        foreach ($methods as $key => $endpoint) {
            $pattern = sprintf("'^%s$'", $key);
            if (preg_match($pattern, $uri)) {
                return $endpoint;
                break;
            }        
        }        
    }

    private static function get_endpoints($path)
    {
        return \util\json::get(\site::SRC_DIR.$path);
    }
    
    private static function get_config($uri)
    {
        $endpoints = \util\json::get(\site::SRC_DIR.'api/endpoints.json');
        $split = preg_split('/\//', $uri);
        return $endpoints[$split[1]];
    }
    
    private static function get_uri()
    {
        return preg_replace('/\/api/', '', $_SERVER['REQUEST_URI']);
    }    
}
