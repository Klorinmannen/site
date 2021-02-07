<?php
namespace user\api;

class controller extends \api\controller 
{
    public function login()
    {
        $user_data = $this->_data;
        if (!isset($user_data['username']) && !$user_data['username'])
            throw new \Exception('Missing username/password', 400);
        if (!isset($user_data['password']) && !$user_data['password'])
            throw new \Exception('Missing username/password', 400);

        $user = \util\user::authenticate($user_data['username'], $user_data['password']);
        
        $record = table('User')->select('JWTKey')->where([ 'UserID' => $user['UserID'] ])->query();

        $jwt = '';
        if ($record['JWTKey'])
            $jwt = \util\jwt::create($user['UserID'], $record['JWTKey']);

        $data['username'] = $user_data['username'];
        $data['jwt'] = $jwt;
        
        return $data;
    }
}
