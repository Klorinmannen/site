<?php
namespace util;

class pdo
{    
    public static function init()
    {
        $config = \util\database::get_config();
        $dsn = \util\database::get_dsn();
        
        $driver_options = [ \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ];

        if (!$pdo = new \PDO( $dsn,
                              $config['username'],
                              $config['password'],
                              $driver_options )) {
            throw new \Exception('Failed to create pdo');        
        }    

        $GLOBALS['pdo'] = $pdo;
    }
}
