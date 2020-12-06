<?php
namespace login;

class view
{
    public static function get_js()
    {
        $filepath = sprintf( '%s/register/%s',
                             \site::SRC_DIR,
                             'signup.js' );
        return \util\js::include($filepath);
    }

    public static function output_default()
	{
        $html = static::output_login_box();
        $html .= static::get_js();
        echo $html;
	}

    public static function output_login_box()
    {
        $filepath = sprintf( '%s/login/%s',
                             \site::SRC_DIR,
                             'login.html' );
        $html = file_get_contents($filepath);
        return $html;
    }    
}
