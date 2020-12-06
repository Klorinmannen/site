<?php
namespace user;

class action
{
    const PAGE_ID = 10;
    
    public static function get_user_by_email($email)
    {
        if (!$email)
            throw new \Exception('Missing email');

        $table = new \util\table('User');
        $table->set_where_fields([ 'Email' => $email ]);
        if (!$record = $table->select())
            return false;
        
        return $record;
    }

    public static function insert($new_user)
    {
        $table = new \util\table('User');
        return $table->insert($new_user);
    }
}
