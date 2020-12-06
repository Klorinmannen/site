<?php
namespace user;

class session
{
    public static function set($username)
    {
        $user_record = \user\action::get_user_by_email($username);        

        $_SESSION['user'] = new \user\profile($user_record);
    }

    public static function init()
    {
        // Default / Guest user
        $_SESSION['user'] = new \user\profile();        
    }
}
