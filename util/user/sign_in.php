<?php
namespace util\user;

class sign_in extends input
{
    private $_pdo;
    private $_table;
    private $_password_field;
    private $_username_field;
    private $_where;
    
    public function __construct($pdo = null)
    {
        $this->_pdo = $pdo;
        $this->_table = null;
        $this->_password_field = null;
        $this->_name_field = null;
        $this->_where = null;

        parent::__construct();
    }

    public function set_pdo($pdo) { $this->_pdo = $pdo; }
    public function set_table(string $table) { $this->_table = $table; }
    public function set_password_field(string $password_field) { $this->_password_field = $password_field; }
    public function set_username_field(string $username_field) { $this->_username_field = $username_field; }
    public function set_where($where) { $this->_where = $where; }

    public function log_in()
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
        $params = [ 'username' => $username ];
        $sql = self::build_sql_query();

        if (!$query = $this->_pdo->prepare($sql))
            return false;
        if (!$query->execute($params))
            return false;
        if (!$record = $query->fetch())
            return '';    
        return $record;
    }

    private function build_sql_query()
    {
        $sql = sprintf( 'SELECT %s FROM %s WHERE %s = :username',
                        $this->_password_field,
                        $this->_table,
                        $this->_name_field );

        if ($this->_where)
            if (is_array($this->_where))
                $sql .= sprintf(' AND %s', implode(' AND ', $this->_where));
            else
                $sql .= sprintf(' AND %s', $this->_where);

        return $sql;
    }
}
