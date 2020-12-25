<?php
namespace api;

class endpoints
{
    public const PARSING_PATTERNS = [ ':id' => '\\d+',
                                      ':string' => '[a-zA-Z]+',
                                      '/' => '\/' ];
    
    public static function parse_config($endpoints)
    {
        array_walk($endpoints, ['self', 'prepare']);
        return $endpoints;
    }

    public static function prepare(&$value)
    {
        foreach (static::PARSING_PATTERNS as $key => $pattern) 
            $value = str_replace($key, $pattern, $value);        
        $value = sprintf('/^%s$/', $value);
    }

    public static function get_config()
    {
        $api_endpoints = sprintf('%sconfig/api_endpoints.json', \site::DIR);        
        return \util\json::parse($api_endpoints);        
    }
}
