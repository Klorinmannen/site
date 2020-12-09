<?php
namespace user;

class action
{
    const PAGE_ID = 10;

    public static function init()
    {
        if (! isset($_SESSION['user']))
            $_SESSION['user'] = new \user\profile();
    }
    
    public static function get_user_by_email($email)
    {
        if (!$email)
            throw new \Exception('Missing email');

        $table = table('User');
        $table->set_where_fields([ 'Email' => $email ]);
        if (!$record = $table->select())
            return false;
        
        return $record;
    }

    public static function insert($new_user)
    {
        return table('User')->insert($new_user);
    }
}
