<?php
namespace api;

class endpoints
{
    public const FUNCTION_PATTERN = [ 'pattern' => '/^.*#\//',
                                      'replace' => '' ];
    public const PATHS_PARSING_PATTERNS = [ '/api/' => '',
                                            '{id}' => '\\d+',
                                            '{name}' => '[a-zA-Z]+',
                                            '/' => '\/' ];
                                          
    public static function parse_config($endpoint_config)
    {
        $parsed_endpoints = [];
        $paths = $endpoint_config['paths'];
        foreach ($paths as $path => $path_info) {
            $function = static::parse_function($path_info['$ref']);
            $parsed_endpoints[$function] = static::parse_path($path);
        }
        return $parsed_endpoints;
    }

    public static function parse_function($desc)
    {
        return preg_replace(static::FUNCTION_PATTERN['pattern'],
                            static::FUNCTION_PATTERN['replace'],
                            $desc);
    }
    
    public static function parse_path($path)
    {
        foreach (static::PATHS_PARSING_PATTERNS as $key => $pattern) 
            $path = str_replace($key, $pattern, $path);        
        return sprintf('/^%s$/', $path);
    }

    public static function get_config()
    {
        $config = \site::API_CONFIG;
        return \util\yaml::parse($config);        
    }
}
