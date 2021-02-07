<?php
namespace util;

class user
{            
    public static function create($username, $password, $api_user = false, $auto_activate = false)
    {
        if (!$username)
            throw new \Exception('Missing username', 400);
        if (!$password)
            throw new \Exception('Missing password', 400);        
        if ($record = static::search_by_name($username))
            throw new \Exception('Username already exists', 400);        

        \util\password::input_validate($password);

        $user['Username'] = $username;
        $user['Password'] = \util\password::hash($password);
        $user['Active'] = 0;

        if ($auto_activate)
            $user['Active'] = -1;
        if ($api_user)
            $user['JWTKey'] = \util\jwt::create_key();

        return static::add($user);       
    }

    public static function authenticate($username, $password)
    {        
        if (!$username || !$password)
            throw new \Exception('Missing username/password');
        if (!$record = static::search_by_name($username))
            throw new \Exception('Invalid username/password');
        if (! \util\password::verify($password, $record['Password']))
            throw new \Exception('Invalid username/password');
        return $record;
    }    
    
    public static function add($user)
    {
        $user['UserID'] = table('User')->insert($user)->query();
        return $user;
    }

    public static function search_by_name($username)
    {
        return table('User')->select(['UserID', 'Password', 'Username', 'Alias'])->where(['Username' => $username, 'Active' => -1])->query();       
    }            
}
