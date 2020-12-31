<?php
namespace api;

class request
{
    private $_uri;
    private $_params;
    private $_controller;
    private $_endpoint_controller;

    public function __construct()
    {
        self::set_request_uri();
        self::set_params();
        self::set_controller();
        self::set_endpoint_controller();
        self::validate_endpoint_controller();
    }

    private function set_endpoint_controller()
    {
        $this->_endpoint_controller = sprintf('%s\api\controller', $this->_controller);
    }

    private function validate_endpoint_controller()
    {
        if (!class_exists($this->_endpoint_controller))
            throw new \Exception('Endpoint does not exist', 400);    
    }

    private function set_controller()
    {
        $this->_controller = array_shift($this->_params);
    }

    private function set_params()
    {
        $this->_params = explode('/', $this->_uri);
    }

    private function set_request_uri()
    {
        $this->_uri = preg_replace('/\/api\//', '', $_SERVER['REQUEST_URI']);
    }

    public function get_params() { return $this->_params; }
    public function get_controller() { return $this->_controller; }
    public function get_endpoint_controller() { return $this->_endpoint_controller; }    
    public function get_uri() { return $this->_uri; }
}
