<?php
namespace login;

class view
{
	public static function output_default()
	{
        $html = static::output_login_box();
        echo $html;
	}

    public static function output_login_box()
    {
        $filepath = sprintf('%s/%s', __DIR__, 'login.html');
        $html = file_get_contents($filepath);
        return $html;
    }    
}
