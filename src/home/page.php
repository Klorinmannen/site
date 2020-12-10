<?php
namespace home;

class page
{
    public static function default()
    {
        $html = 'Development site<br>';
        $html .= 'This is a home page';
        echo $html;
    }
}
