<?php
namespace user;

class model
{
    public static function get_user_by_email($email)
    {
        return table('User')->select()->where([ 'Email' => $email, 'Active' => -1 ])->query();
    }

    public static function insert($new_user)
    {
        return table('User')->insert($new_user)->query();
    }
}
