<?php
namespace user\input;

class signup
{
    public const USERNAME_DEFAULT_FIELD = 'username';
    public const PASSWORD_DEFAULT_FIELD = 'password';
    public const EMAIL_DEFAULT_FIELD = 'email';
    
    private $_username_field;
    private $_password_field;
    private $_email_field;
    
    private $_name_pattern;  
    private $_email_filter;
    
    private $_password_secret;
    private $_password_hash_algorithm;
    private $_password_hash_options;
    
    private $_password_pattern_any;
    private $_password_pattern_length;
    private $_password_pattern_nbr;
    private $_password_pattern_lcc;
    private $_password_pattern_ucc;
    private $_password_pattern_sc;
    
    public function __construct( $email_field = self::EMAIL_DEFAULT_FIELD,
                                 $username_field = self::USERNAME_DEFAULT_FIELD,
                                 $password_field = self::PASSWORD_DEFAULT_FIELD ) 
    {
        $this->_email_field = $email_field;
        $this->_username_field = $username_field;
        $this->_password_field = $password_field;
        self::init();
    }

    private function init()
    {
        $this->_name_pattern = '/[\w]+/';        
        $this->_email_filter = \FILTER_VALIDATE_EMAIL;
        
        $this->_password_hash_algorithm = \PASSWORD_DEFAULT;
        $this->_password_hash_options = [ 'cost' => 10 ];

        $this->_password_secret = '';
        $this->_password_pattern_any = '/[\w]+/';
        $this->_password_pattern_length = '/.{8,}/';
        $this->_password_pattern_nbr = '/[0-9]+/';
        $this->_password_pattern_lcc = '/[a-z]+/';
        $this->_password_pattern_ucc = '/[A-Z]+/';
        $this->_password_pattern_sc = '/[^\w]+|[_]+/';        
    }

    public function set_email_field(string $email_field) { $this->_email_field = $email_field; }
    public function get_email_field() { return $this->_email_field; }
    
    public function set_username_field(string $username_field) { $this->_username_field = $username_field; }
    public function get_username_field() { return $this->_username_field; }
    
    public function set_password_field(string $password_field) { $this->_password_field = $password_field; }
    public function get_password_field() { return $this->_password_field; }
        
    public function set_password_any_pattern(string $password_pattern) { $this->_password_pattern_any = $password_pattern; }
    public function set_password_length_pattern(string $password_pattern) { $this->_password_pattern_length = $password_pattern; }
    public function set_password_lcc_pattern(string $password_pattern) { $this->_password_pattern_lcc = $password_pattern; }
    public function set_password_ucc_pattern(string $password_pattern) { $this->_password_pattern_ucc = $password_pattern; }
    public function set_password_nbr_pattern(string $password_pattern) { $this->_password_pattern_nbr = $password_pattern; }
    public function set_password_sc_pattern(string $password_pattern) { $this->_password_pattern_sc = $password_pattern; }    
    public function get_password_patterns()
    {
        return [ 'any' => $this->_password_pattern_any,
                 'length' => $this->_password_pattern_length,
                 'lcc' => $this->_password_pattern_lcc,
                 'ucc' => $this->_password_pattern_ucc,
                 'nbr' => $this->_password_pattern_nbr,
                 'sc' => $this->_password_pattern_sc ];
    }

    public function set_password_hash_algorithm(string $algorithm) { $this->_password_hash_algorithm = $algorithm; }
    public function get_password_hash_algorithm() { return $this->_password_hash_algorithm; }

    public function set_password_hash_options(array $options) { $this->_password_hash_options = $options; }
    public function get_password_hash_options() { return $this->_password_hash_options; }        
    
    public function get_inputs()
    {
        if (!$email = self::get_input_field($this->_email_field))
            throw new \Exception('Missing email');
        if (!$username = self::get_input_field($this->_username_field))
            throw new \Exception('Missing username');
        if (!$password = self::get_input_field($this->_password_field))
            throw new \Exception('Missing password');

        $email = trim($email);
        if (! self::validate_email($email))
            throw new \Exception('Invalid username');        
        $username = trim($username);
        if (! self::validate_username($username))
            throw new \Exception('Invalid username');

        self::validate_password($password);
        if (!$password = self::treat_password($password))
            return false;
        
        return [ 'email' => $email,
                 'username' => $username,
                 'password' => $password ];
    }

    private function get_input_field($input_field)
    {
        if (!isset($_REQUEST[$input_field]))
            return false;
        if (!$field = $_REQUEST[$input_field])
            return false;
        return $field;
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
            throw new \Exception('Invalid password');
        if (! self::match_pattern($this->_password_pattern_length, $password))
            throw new \Exception('Invalid password, must have atleast 8 characters');
        if (! self::match_pattern($this->_password_pattern_nbr, $password))
            throw new \Exception('Invalid password, must have a number');
        if (! self::match_pattern($this->_password_pattern_lcc, $password))
            throw new \Exception('Invalid password, must have a lower case character');
        if (! self::match_pattern($this->_password_pattern_ucc, $password))
            throw new \Exception('Invalid password, must have a upper case character');        
        if (! self::match_pattern($this->_password_pattern_sc, $password))
            throw new \Exception('Invalid password, must have a special case character');        
        return true;
    }
    
    private function match_pattern($pattern, $subject)
    {
        if (preg_match($pattern, $subject) === 1)
            return true;
        return false;
    }    

    private function treat_password($password)
    {        
        if ($this->_password_secret)
            return null;
        return self::hash_password($password);
    }
    
    private function add_secret_to_password($password)
    {
        return $password.$this->_password_secret;
    }
    
    private function hash_password($password)
    {
        return password_hash($password, $this->_password_hash_algorithm, $this->_password_hash_options);
    }
}
