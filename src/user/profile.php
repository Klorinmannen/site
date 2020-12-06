<?php
namespace user;

class profile
{
    const SYSTEM_USER_ID = 1;

    private $_user_name = '';
    private $_user_email = '';
	private $_user_id = 0;
	private $_page_id = 0;
    private $_user_file_path = '';
    
	public function __construct($user_email, $user_name, $user_id, $page_id = 2) 
	{
        $this->_user_email = $user_email;
		$this->_user_name = $user_name;
		$this->_user_id = $user_id;
		$this->_page_id = $page_id;
        
        $path = sprintf( '%s%s/',
                         \upload\file::USER_FILE_PATH,
                         $user_id );
        
        $this->_user_file_path = $path;
    }

    public function set_user_email($user_email) { $this->_user_email = $user_email; }
	public function set_user_name($user_name) { $this->_user_name = $user_name; }
	public function set_user_id($user_id) { $this->_user_id = $user_id; }
	public function set_page_id($page_id) { $this->_page_id = $page_id; }

    public function get_user_file_path() { return $this->_user_file_path; }
    public function get_user_email() { return $this->_user_email; }
	public function get_user_name() { return $this->_user_name; }
	public function get_user_id() { return $this->_user_id; }
	public function get_page_id() { return $this->_page_id; }
}
