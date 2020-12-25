<?php
namespace user;

class model
{
    const PAGE_ID = 10;
    
    public static function get_user_by_email($email)
    {
        $table = table('User');
        $table->set_where_fields([ 'Email' => $email, 'Active' => -1 ]);       
        return $table->select();
    }

    public static function insert($new_user)
    {
        return table('User')->insert($new_user);
    }
}
