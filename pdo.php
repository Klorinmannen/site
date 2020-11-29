<?php
namespace util;

class pdo
{
    private $_pdo;

    public function __construct()
    {
        self::init_pdo();
        self::make_global();
    }

    public function init_pdo()
    {
        $config = database()->get_config();
        $dsn = database()->get_dsn();
        
        $driver_options = [ \PDO::ATTR_DEFAULT_FECTH_MODE => \PDO::FETCH_ASSOC,
                            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION ];

        if (!$pdo = new \PDO( $dsn,
                              $config['username'],
                              $config['password'],
                              $driver_options )) {
            throw new \Exception('Failed to create pdo');        
        }    
    
        $this->_pdo = $pdo;
    }

    public function get_pdo()
    {
        return $this->_pdo;
    }
    
    private function make_global()
    {
        $GLOBALS['pdo'] = $this;        
    }
}
