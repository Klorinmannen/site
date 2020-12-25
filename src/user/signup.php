<?php
namespace user;

class signup extends input
{
    public const EMAIL_DEFAULT_HTML_NAME = 'email';
    
    private $_html_email_name;
    
    private $_username_pattern;  
    private $_email_filter;
    
    private $_password_hash_algorithm;
    private $_password_hash_options;    
    private $_password_pattern_any;
    private $_password_pattern_length;
    private $_password_pattern_nbr;
    private $_password_pattern_lcc;
    private $_password_pattern_ucc;
    private $_password_pattern_sc;
    private $_password_exception_msg;

    private $_table;
    private $_where;
    
    public function __construct($html_email_name = self::EMAIL_DEFAULT_HTML_NAME)
    {
        $this->_html_email_name = $html_email_name;
        parent::__construct();
        self::init();
    }

    private function init()
    {
        $this->_username_pattern = '/.+/';        
        $this->_email_filter = \FILTER_VALIDATE_EMAIL;
        
        $this->_password_hash_algorithm = \PASSWORD_DEFAULT;
        $this->_password_hash_options = [ 'cost' => 10 ];

        $this->_password_pattern_any = '/.+/';
        $this->_password_pattern_length = '/.{8,}/';
        $this->_password_pattern_nbr = '/[0-9]+/';
        $this->_password_pattern_lcc = '/[a-z]+/';
        $this->_password_pattern_ucc = '/[A-Z]+/';
        $this->_password_pattern_sc = '/[^\w]+|[_]+/';
        
        $this->_password_exception_msg = 'The password must be 8 characters long.';
        $this->_password_exception_msg .= '\nContain one upper and lower case character.';
        $this->_password_exception_msg .= '\nOne special case character and one number';

        $this->_table = 'User';
        $this->_where = 'Email';
    }

    public function set_html_email_name(string $html_email_name) { $this->_html_email_name = $html_email_name; }            

    public function set_password_any_pattern(string $password_pattern) { $this->_password_pattern_any = $password_pattern; }
    public function set_password_length_pattern(string $password_pattern) { $this->_password_pattern_length = $password_pattern; }
    public function set_password_lcc_pattern(string $password_pattern) { $this->_password_pattern_lcc = $password_pattern; }
    public function set_password_ucc_pattern(string $password_pattern) { $this->_password_pattern_ucc = $password_pattern; }
    public function set_password_nbr_pattern(string $password_pattern) { $this->_password_pattern_nbr = $password_pattern; }
    public function set_password_sc_pattern(string $password_pattern) { $this->_password_pattern_sc = $password_pattern; }    
    public function set_password_hash_algorithm(string $algorithm) { $this->_password_hash_algorithm = $algorithm; }
    public function set_password_hash_options(array $options) { $this->_password_hash_options = $options; }

    public function set_table(string $table) { $this->_table = $table; }
    public function set_where(array $where) { $this->_where = $where; }
    
    public function get_inputs()
    {
        if (!$email = parent::get_input_name($this->_html_email_name))
            throw new \Exception('Missing email');
        if (!$username = parent::get_input_name($this->_html_username_name))
            throw new \Exception('Missing username');
        if (!$password = parent::get_input_name($this->_html_password_name))
            throw new \Exception('Missing password');

        $email = trim($email);
        if (! self::validate_email($email))
            throw new \Exception('Invalid username / email');        
        $username = trim($username);
        if (! self::validate_username($username))
            throw new \Exception('Invalid username / email');
        if ($user_record = self::search_for_user($email))
            throw new \Exception('Invalid username / email');        
        if (! self::validate_password($password))
            throw new \Exception($this->_password_exception_msg);
        
        $password = self::treat_password($password);

        $inputs = [ 'email' => $email,
                    'username' => $username,
                    'password' => $password ];

        return $inputs;        
    }
    
    private function search_for_user($email)
    {
        return table($this->_table)->select()->where([ $this->_where => $email ])->query();        
    }
    
    private function validate_email($email)
    {
        return filter_var($email, $this->_email_filter);
    }
    
    private function validate_username($username)
    {        
        return self::match_pattern($this->_username_pattern, $username);
    }

    private function validate_password($password)
    {
        if (! self::match_pattern($this->_password_pattern_any, $password))
            return false;
        if (! self::match_pattern($this->_password_pattern_length, $password))
            return false;
        if (! self::match_pattern($this->_password_pattern_nbr, $password))
            return false;
        if (! self::match_pattern($this->_password_pattern_lcc, $password))
            return false;
        if (! self::match_pattern($this->_password_pattern_ucc, $password))
            return false;
        if (! self::match_pattern($this->_password_pattern_sc, $password))
            return false;

        return true;
    }
    
    private function match_pattern($pattern, $subject)
    {
        return (preg_match($pattern, $subject) === 1);
    }    

    private function treat_password($password)
    {        
        if ($this->_password_secret)
            $password = parent::hash_password_with_secret($password);        
        return self::hash_password($password);
    }
        
    private function hash_password($password)
    {
        return password_hash($password, $this->_password_hash_algorithm, $this->_password_hash_options);
    }
}
