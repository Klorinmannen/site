<?php
namespace user;

class sign_in extends input
{
    private $_pdo;
    private $_table;
    private $_pass_field;
    private $_name_field;
    private $_where_sql;
    
    public function __construct($pdo = null)
    {
        $this->_pdo = $pdo;
        $this->_table = null;
        $this->_pass_field = null;
        $this->_name_field = null;
        $this->_extra_where = null;

        parent::__construct();
    }

    public function set_pdo(object $pdo) { $this->_pdo = $pdo; }
    public function get_pdo() { return $this->_pdo; }

    public function set_table(string $table) { $this->_table = $table; }
    public function get_table() { return $this->_table; }

    public function set_password_field(string $field) { $this->_pass_field = $field; }
    public function get_password_field() { return $this->_pass_field; }

    public function set_username_field(string $field) { $this->_name_field = $field; }
    public function get_username_field() { return $this->_name_field; }

    public function set_where_sql(array $where_sql) { $this->_where_sql = $where_sql; }
    public function get_where_sql() { return $this->_where_sql; }
    
    public function log_in()
    {
        if (! self::check_settings())
            return false;        

        if (!$username = parent::get_input_field($this->_username_field))
            throw new \Exception('Missing username');
        if (!$password = parent::get_input_field($this->_password_field))
            throw new \Exception('Missing password');

        if (! self::authenticate($username, $password))
            return false;
        return true;
    }

    private function check_settings()
    {
        if ($this->_pdo === null)
            return false;       
        if ($this->_table === null)
            return false;        
        if ($this->_pass_field === null)
            return false;
        if ($this->_name_field === null)
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
        if (!password_verify($password, $record[$this->_pass_field]))
            throw new \Exception('Invalid username or password');
        return true;
    }

    private function search_for_user($username)
    {
        $params = [ 'username' => $username ];
        $sql = self::build_sql_query();

        if (!$query = $this->_pdo->prepare($sql))
            return false;
        if (!$query->execute($params))
            return false;
        if (!$record = $query->fetch(\PDO::FETCH_ASSOC))
            return '';    
        return $record;
    }

    private function build_sql_query()
    {
        $sql = sprintf('SELECT %s', $this->_password_field);
        $sql .= sprintf(' FROM %s', $this->_table);
        $sql .= sprintf(' WHERE %s = :username', $this->_name_field);
        if ($this->_where_sql)
            $sql .= sprintf(' AND %s', implode(' AND ', $this->_where_sql));
        return $sql;
    }
}
