<?php
namespace user;

class input
{
    public const USERNAME_DEFAULT_HTML_FIELD = 'username';
    public const PASSWORD_DEFAULT_HTML_FIELD = 'password';

    protected $_username_field;
    protected $_password_field;

    public function __construct($username_field = self::USERNAME_DEFAULT_HTML_FIELD,
                                $password_field = self::PASSWORD_DEFAULT_HTML_FIELD)
    {
        $this->_username_field = $username_field;
        $this->_password_field = $password_field;
    }

    public function set_username_field(string $username_field) { $this->_username_field = $username_field; }
    public function get_username_field() { return $this->_username_field; }
    
    public function set_password_field(string $password_field) { $this->_password_field = $password_field; }
    public function get_password_field() { return $this->_password_field; }
    
    protected function get_input_field($input_field)
    {
        if (!isset($_REQUEST[$input_field]))
            return false;
        if (!$field = $_REQUEST[$input_field])
            return false;
        return $field;
    }    
}
