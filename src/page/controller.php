<?php
namespace page;

class controller
{
	public static function get_page() 
	{
        $page_id = static::get_page_id();
        return \page\model::get_by_id($page_id);
    }

    public static function get_page_id()
    {
        $page_id = 0;
        if (isset($_SERVER['page_id']))
            $page_id = $_SERVER['page_id'];
        if ($input_page_id = validate_id_input('page_id'))
            $page_id = $input_page_id;
        if (!user()->has_access($page_id))
            $page_id = user()->get_page_id();              
        return $page_id;
    }
}
