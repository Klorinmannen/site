<?php
namespace util;

class json
{
    public static function get_string($path)
    {
        if (!$path)
            throw new \Exception('Missing json path', 500);
        $json_string = file_get_contents($path);
        if ($json_string === false)
            throw new \Exception('Failed to read json file', 500);
        return $json_string;
    }

    public static function decode(string $json_string, bool $array = true)
    {
        $content = json_decode($json_string, $array);
        if ($content === NULL)
            throw new \Exception('Failed to decode json string', 500);
        return $content;
    }
    
    public static function parse(string $path)
    {
        if (!$path)
            throw new \Exception('Missing json path', 500);
        $json_string = static::get_string($path);
        return static::decode($json_string);
    }
}
