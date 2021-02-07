<?php
namespace api;

class controller
{
    private $_params;
    private $_method;
    protected $_data;
    
    public function __construct($method, $params, $data)
    {
        $this->_params = $params;
        $this->_method = $method;
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
