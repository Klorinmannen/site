<?php
namespace user;

class action
{
    public static function init()
    {
        if (! static::is_set())
            static::create_and_set_guest_user();
    }

    public static function create_and_set_guest_user()
    {
        $_SESSION['user'] = new \user();
    }
    
    public static function is_set()
    {
        return isset($_SESSION['user']);
    }
}
