<?php
namespace login;

class action
{
	public static function login()
	{
        $sign_in = new \user\sign_in();
        if ($username = $sign_in->login())
            $something;
        
		\user\session::set($username);

		return true;
	}
}
