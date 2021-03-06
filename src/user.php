<?php

class user
{
    const SYSTEM_USER_ID = 1;

    // Default settings
    private $_user_name = 'Guest';
    private $_user_email = 'Guest';
	private $_user_id = 0;
	private $_page_id = 2;
    private $_user_file_path = '';
    private $_page_permission = [ 1, 2, 3, 13 ];
    private $_status = false;
    private $_record = [];
    
	public function __construct(array $record = []) 
	{
        $this->_record = $record;
        if ($this->_record)
            self::settings_from_record();
    }
    
    private function settings_from_record()
    {
        $this->_user_email = $this->_record['Email'];
        $this->_user_name = $this->_record['Username'];
        $this->_user_id = $this->_record['UserID'];
        $this->_page_id = $this->_record['PageID'];               
        $this->_user_file_path = $this->_record['FilePath'];
        $this->_page_permission = $this->_record['PagePermission'];
        $this->_status = true;
    }

    public function has_access($ids = 0)
    {
        if (is_array($ids))
            return (bool)array_intersect($ids, $this->_page_permission);
        return (bool)in_array($ids, $this->_page_permission);
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
    public function get_jwt_key() { return $this->_jwt_key; }
    public function get_status() { return $this->_status; }
    public function get_page_permission() {return $this->_page_permission; }
}

