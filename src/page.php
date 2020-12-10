<?php

class page 
{
	public static function get() 
	{
        $page_id = static::get_page_id();
        return static::get_page($page_id);
    }

    public static function get_page_id()
    {
        $page_id = 0;
        if (isset($_SERVER['page_id']))
            $page_id = $_SERVER['page_id'];
        if ($input_page_id = validate_id_input('page_id'))
            $page_id = $input_page_id;
        if (!$page_id && !user()->check_permission($page_id))
            $page_id = user()->get_page_id();              
        return $page_id;
    }
    
    public static function get_page($page_id)
    {
        return \page\table::get($page_id);
    }
}
