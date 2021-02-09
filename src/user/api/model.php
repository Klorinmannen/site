<?php
namespace user;

class model
{
    private $_table = null;

    public function __construct()
    {
        $this->_table = table('User');
    }

    public static function get_user_by_email($email)
    {
        return $this->_table->select()->where([ 'Email' => $email, 'Active' => -1 ])->query();
    }

    public static function insert($new_user)
    {
        return $this->_table->insert($new_user)->query();
    }
}
