<?php
namespace api;

class config
{
    public const CONFIG_PATH = '/var/www/site/html/docs/api/';
    public const MAIN_CONFIG_PATH = '/var/www/site/html/docs/api/main.yml';

    private $_routes = null;
    
    public function __construct()
    {
        self::set_routes();
    }

    public function set_routes()
    {
        $main_conf_file = self::get_main_config();
        $paths = $main_conf_file['paths'];
        $routes = [];
        foreach ($paths as $uri_path => $path) {
            $endpoints = explode('#/', $path['$ref']);
            $conf_file = $endpoints[0];
            $conf_path = $endpoints[1];
            $controller = str_replace('.yml', '', $conf_file);

            $ref_config = self::get_referenced_config($conf_file);
            $conf_details = $ref_config[$conf_path];            
            foreach ($conf_details as $method => $details)
                $routes[$method][$uri_path] = $details['operationId'];
        }           
        $this->_routes = $routes;
    }
    
    public function get_main_config()
    {
        if (!is_readable(static::MAIN_CONFIG_PATH))
            throw new \Exception('Internal server error', 500);
        return \util\yaml::parse(static::MAIN_CONFIG_PATH);
    }

    public function get_referenced_config($referenced)
    {
        $file = static::CONFIG_PATH.$referenced;
        if (!is_readable($file))
            throw new \Exception('Internal server error', 500);
        return \util\yaml::parse($file);
    }   

    public function get_routes() { return $this->_routes; }
}
