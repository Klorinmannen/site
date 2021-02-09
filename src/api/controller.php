<?php
namespace api;

class controller
{
    private $_params;
    private $_method;
    protected $_model;
    protected $_data;
    
    public function __construct($method, $params, $data, $resource_model)
    {
        $this->_params = $params;
        $this->_method = $method;
        $this->_model = new $resource_model();
        $this->_data = $data;
    }

    public function call()
    {
        $call = $this->_method;
        $response = static::$call( ...$this->_params);

        return self::get_json_encoded_response($response);
    }

    public function get_json_encoded_response($response)
    {
        $no_pretty_print = 1;
        return \util\json::encode($response, $no_pretty_print);
    }
}
