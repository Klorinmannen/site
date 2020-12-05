<?php
namespace util;

class database
{
    public static function get_config()
    {
        return [ 'server_name' => 'serverName',
                 'server_port' => 9999,
                 'database_name' => 'databaseName',
                 'username' => 'username',
                 'password' => 'password' ];
    }

    public function get_dsn()
    {
        $config = static::get_config();
        return sprintf( 'mysql:host=%s;port=%d;dbname=%s', 
                        $this->_config['server_name'], 
                        $this->_config['server_port'], 
                        $this->_config['database_name'] );
    }
}
