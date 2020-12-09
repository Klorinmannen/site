<?php

class page 
{
	const DEFAULT_PAGE_ID = 1;
    
	public static function get() 
	{
        $page_id = static::DEFAULT_PAGE_ID;        
        if (isset($_SERVER['page_id']))
            $page_id = $_SERVER['page_id'];
        if ($input_page_id = validate_id_input('page_id'))
            $page_id = $input_page_id;        

        $page = \page\action::get($page_id);
        return $page;
    }
}
