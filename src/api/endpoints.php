<?php
namespace api;

class endpoints
{
    public const ID = ':id';
    public const FWSL = '/';
    
    public static function parse($endpoints)
    {
        array_walk($endpoints, ['self', 'prepare']);
        return $endpoints;
    }

    public static function prepare(&$value)
    {
        $value = str_replace(static::ID, '\\d', $value);
        $value = str_replace(static::FWSL, '\/', $value);
        $value = sprintf('/^%s$/', $value);
    }
}
