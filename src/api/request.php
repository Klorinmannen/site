<?php
namespace api;

class request
{
    public static function parse()
    {
        $uri = static::parse_uri();
        return explode('/', $uri);
    }

    public static function parse_uri()
    {
        return preg_replace('/\/api\//', '', $_SERVER['REQUEST_URI']);
    }
}
