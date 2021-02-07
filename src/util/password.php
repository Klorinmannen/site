<?php
namespace util;

class password
{
    public const HASH_ALG = 'SHA256';
    public const SERVER_SECRET = 'iOYoJQ+qldvTZDCSweGJP/p8YAU=';   

    public static function input_validate($password)
    {
        if (!match_pattern('/.{8,}/', $password))
            throw new \Exception('Password has to be atleast 8 characters long', 400);
        if (!match_pattern('/[0-9]+/', $password))
            throw new \Exception('Password has to contain atleast 1 number', 400);
        if (!match_pattern('/[a-z]+/', $password))
            throw new \Exception('Password has to contain atleast 1 lower case character', 400);
        if (!match_pattern('/[A-Z]+/', $password))
            throw new \Exception('Password has to contain atleast 1 upper case character', 400);
        if (!match_pattern('/[^\w]+|[_]+/', $password))
            throw new \Exception('Password has to contain atleast 1 special character', 400);
        return true;
    }

    public static function hash($password)
    {
        $hashed_password = static::hash_with_secret($password);
        return password_hash($hashed_password, \PASSWORD_DEFAULT);
    }

    public static function verify($password, $stored_password)
    {
        $hashed_password = static::hash_with_secret($password);
        return password_verify($hashed_password, $stored_password);
    }

    public static function hash_with_secret($password)
    {
        return hash_hmac(static::HASH_ALG, $password, static::SERVER_SECRET);
    }
}
