<?php
namespace api;

class controller
{
    private $_params;
    private $_controller;
    private $_lc_method;
    private $_main_config;
    private $_uri;
    private $_endpoint;
    private $_response;

    public const PATH_PATTERNS = [ '{id}' => '\\d+',
                                   '{name}' => '[a-zA-Z]+',
                                   '/' => '\/' ];
    
    public function __construct($request)
    {
        $this->_uri = $request->get_uri();
        $this->_params = $request->get_params();
        $this->_controller = $request->get_controller();
        $this->_lc_method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->_main_config = \api\config::get_main_config();
    }

    public function map_endpoint()
    {
        $paths = $this->_main_config['paths'];
        foreach ($paths as $path => $reference) {

            $path_pattern = self::get_path_pattern($path);
            if (preg_match($path_pattern, $this->_uri) === 1) {

                $ref_parts = explode('#/', $reference['$ref']);
                if (str_replace('.yml', '', $ref_parts[0]) == $this->_controller) {
                    self::set_endpoint($ref_parts);
                    return;
                }
            }            
        }

        // If no endpoint is found, exit
        throw new \Exception('No endpoint found', 400);
    }

    public function get_path_pattern($path)
    {
        $path = ltrim($path, '/');
        foreach (static::PATH_PATTERNS as $sign => $pattern)
            $path = str_replace($sign, $pattern, $path);
        return sprintf('/^%s$/', $path);
    }

    public function set_endpoint($ref_parts)
    {
        $referenced_endpoints = \api\config::get_referenced_config($ref_parts[0]);
        $endpoint = $ref_parts[1];
        
        if (! isset($referenced_endpoints[$endpoint]))
            throw new \Exception('Endpoint does not exist', 400);
        if (! isset($referenced_endpoints[$endpoint][$this->_lc_method]))
            throw new \Exception('Endpoint method does not exist', 400);
        if (! isset($referenced_endpoints[$endpoint][$this->_lc_method]['operationId']))
            throw new \Exception('Internal server error ', 500);
        
        $this->_endpoint = $referenced_endpoints[$endpoint][$this->_lc_method]['operationId'];
    }

    public function call_endpoint()
    {
        $call = $this->_endpoint;
        $this->_response = static::$call( ...$this->_params);
    }

    public function get_json_encoded_response()
    {
        return \util\json::encode($this->_response, 0);
    }

    public function get_raw_response()
    {
        return $this->_response;
    }
}
