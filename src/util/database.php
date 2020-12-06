<?php
namespace util;

class database
{
    const CONFIG_FILE_PATH = 'config/db.json';

    public static function get_config_path()
    {
        return sprintf('%s%s', \site::DIR, static::CONFIG_FILE_PATH);
    }
    
    public static function get_config()
    {
        $config_path = static::get_config_path();
        $json_string = file_get_contents($config_path);
        if ($json_string === false)
            throw new \Exception('Failed to get db config');

        $config = json_decode($json_string, true);
        if ($config === NULL)
            throw new \Exception('Failed to decode json');

        return $config;
    }

    public static function get_dsn()
    {
        $config = static::get_config();
        return sprintf( 'mysql:host=%s;port=%d;dbname=%s', 
                        $config['server_name'], 
                        $config['server_port'], 
                        $config['database_name'] );
    }
}
