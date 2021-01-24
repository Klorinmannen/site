<?php
namespace api;

class request
{
    private $_uri = null;
    private $_method = null;
    private $_input_data = null;
    private $_json_data = null;
    
    public function __construct()
    {
        self::set_request_uri();
        self::set_method();
        self::set_data();
    }

    private function set_data()
    {
        switch ($this->_method) {
        case 'POST':
        case 'PUT':
        case 'PATCH':
            $this->_input_data = file_get_contents('php://input');
            if ($this->_input_data)
                $this->_json_data = \util\json::decode($this->_input_data);
            break;
        default:
            $this->_input_data = '';
            break;
        }
    }
    
    private function set_method()
    {
        $this->_method = $_SERVER['REQUEST_METHOD'];
    }
    
    private function set_request_uri()
    {
        $this->_uri = str_replace('/api', '', $_SERVER['REQUEST_URI']);
    }
    
    public function get_data() { return $this->_json_data; }
    public function get_uri() { return $this->_uri; }
    public function get_method() { return $this->_method; }
}
