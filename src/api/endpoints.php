<?php
namespace api;

class endpoints
{
    public const ID = ':id';
    public const STRING = ':string';
    public const FWSL = '/';
    
    public static function parse_config($endpoints)
    {
        array_walk($endpoints, ['self', 'prepare']);
        return $endpoints;
    }

    public static function prepare(&$value)
    {
        $value = str_replace(static::ID, '\\d+', $value);
        $value = str_replace(static::STRING, '[a-zA-Z]+', $value);
        $value = str_replace(static::FWSL, '\/', $value);
        $value = sprintf('/^%s$/', $value);
    }

    public static function get_config()
    {
        $api_endpoints = sprintf('%sconfig/api_endpoints.json', \site::DIR);        
        return \util\json::parse($api_endpoints);        
    }
}
