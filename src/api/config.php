<?php
namespace api;

class config
{
    public const CONFIG_PATH = '/var/www/site/html/docs/api/';
    public const MAIN_CONFIG_PATH = '/var/www/site/html/docs/api/main.yml';

    public static function get_main_config()
    {
        return \util\yaml::parse(static::MAIN_CONFIG_PATH);
    }

    public static function get_referenced_config($referenced)
    {
        $file = static::CONFIG_PATH.$referenced;
        if (!is_readable($file))
            throw new \Exception('Internal server error', 500);
        return \util\yaml::parse($file);
    }
}
