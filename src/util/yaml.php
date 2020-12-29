<?php
namespace util;

class yaml
{
    public static function parse($filepath)
    {
        if(!$encoded = yaml_parse_file($filepath))
            throw new \Exception('Yaml failed to parse', 500);
        return $encoded;
    }
}
