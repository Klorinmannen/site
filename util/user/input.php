<?php
namespace uitl\user;

class input
{
    public const USERNAME_DEFAULT_HTML_NAME = 'username';
    public const PASSWORD_DEFAULT_HTML_NAME = 'password';

    public const SECRET_HASH_METHOD = 'sha256';
    
    protected $_html_username_name;
    protected $_html_password_name;

    protected $_password_secret = 'iOYoJQ+qldvTZDCSweGJP/p8YAU=';
    
    public function __construct($html_username_name = self::USERNAME_DEFAULT_HTML_NAME,
                                $html_password_name = self::PASSWORD_DEFAULT_HTML_NAME)
    {
        $this->_html_username_name = $html_username_name;
        $this->_html_password_name = $html_password_name;
    }

    public function set_html_username_name(string $html_username_name) { $this->_html_username_name = $html_username_name; }
    public function set_html_password_name(string $html_password_name) { $this->_html_password_name = $html_password_name; }

    protected function get_input_name($input_name)
    {
        if (!isset($_REQUEST[$input_name]))
            return false;
        if (!$name = $_REQUEST[$input_name])
            return false;
        return $name;
    }    

    protected function hash_password_with_secret($password)
    {
        $password_with_secret = sprintf('%s%s', $password, $this->_password_secret);
        return hash(self::SECRET_HASH_METHOD, $password_with_secret);
    }
}
