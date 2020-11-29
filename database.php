<?php
namespace util;

class database
{
    private $_config;
    private $_dsn;
    
    public function __construct()
    {
        self::init_config();
        self::init_dsn();
        self::make_global();
    }    
    
    private function init_config()
    {
        $this->_config = [ 'server_name' => 'serverName',
                           'server_port' => 9999,
                           'database_name' => 'databaseName',
                           'username' => 'username',
                           'password' => 'password' ];
    }

    private function init_dsn()
    {
        $this->_dsn = sprintf( 'mysql:host=%s;port=%d;dbname=%s', 
                               $this->_config['server_name'], 
                               $this->_config['server_port'], 
                               $this->_config['database_name'] );
    }
    
    public function get_dsn()
    {
        return $this->_dsn;
    }
    
    public function get_config()
    {
        return $this->_config;
    }

    private function make_global()
    {
        $GLOBALS['database'] = $this;
    }
}
