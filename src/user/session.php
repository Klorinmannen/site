<?php
namespace user;

class session
{
    public static function set($username)
    {
        $user = \user\action::get_user_by_email($username);        

        $_SESSION['user'] = new \user\profile( $email,
                                               $user['Name'],
                                               $user['UserID'] );
    }
}
