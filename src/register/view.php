<?php
namespace register;

class view
{
    public static function get_html()
    {
        $filepath = sprintf('%s/register/%s',
                            \site::SRC_DIR,
                            'signup.html');
        $html = file_get_contents($filepath);
        if ($html === false)
            throw new \Exception('Failed to retrieve html');
        return $html;
    }

    public static function get_js()
    {
        $filepath = sprintf('%s/register/%s',
                            \site::SRC_DIR,
                            'signup.js');
        return \util\js::include($filepath);
    }
    
    public static function output_default()
	{
        $html = static::get_html();
        $html .= static::get_js();
        echo $html;
    }
}
