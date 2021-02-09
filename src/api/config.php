<?php
namespace api;

class config
{
    public const CONFIG_PATH = '/var/www/site/html/conf/';
    public const MAIN_CONFIG_PATH = '/var/www/site/html/conf/main.yml';

    private $_routes = null;
    
    public function __construct()
    {
        // Quick access no need to parse the configuration again
        if (isset($_SESSION['api_config']))
            $this->_routes = $_SESSION['api_config'];
        else 
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
            $conf_name = $endpoints[1];
            $resource = str_replace('.yml', '', $conf_file);

            $ref_config = self::get_referenced_config($conf_file);
            $conf_details = $ref_config[$conf_name];            
            foreach ($conf_details as $method => $details) {
                $routes[$method][$uri_path]['endpoint'] = $details['operationId'];
                $routes[$method][$uri_path]['security'] = $details['security'];
                $routes[$method][$uri_path]['resource'] = $resource;
            }
        }
        
        $_SESSION['api_config'] = $routes;
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
