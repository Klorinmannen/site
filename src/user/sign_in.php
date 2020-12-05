<?php
namespace user;

class sign_in extends input
{
    private $_table;
    private $_where;
    private $_password_field;
    private $_username_field;
    
    public function __construct()
    {
        $this->_table = null;
        $this->_where = null;
        $this->_password_field = null;
        $this->_name_field = null;

        parent::__construct();
    }

    public function set_pdo($pdo) { $this->_pdo = $pdo; }
    public function set_table(string $table) { $this->_table = $table; }
    public function set_where($where) { $this->_where = $where; }
    public function set_password_field(string $password_field) { $this->_password_field = $password_field; }
    public function set_username_field(string $username_field) { $this->_username_field = $username_field; }

    public function login()
    {
        if (!$username = parent::get_input_name($this->_html_username_name))
            throw new \Exception('Missing username');
        if (!$password = parent::get_input_name($this->_html_password_name))
            throw new \Exception('Missing password');
        if (! self::authenticate($username, $password))
            return false;

        return true;
    }
       
    private function authenticate($username, $password)
    {
        $record = self::search_for_user($username);
        if ($record === false)
            return false;
        if ($record === '')
            throw new \Exception('Invalid username or password');        
        
        if ($this->_password_secret)
            $password = parent::hash_password_with_secret($password);
        
        if (!password_verify($password, $record[$this->_password_field]))
            throw new \Exception('Invalid username or password');

        return true;
    }
    
    private function search_for_user($username)
    {
        $table = new \util\table($this->_table);        
        $table->set_where_fields([ $this->_name_field => $username ]);
        return $table->select($this->_password_field);
    }
}
