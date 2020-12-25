<?php
namespace login;

class action
{
	public static function login()
	{
        // setup login object
        $sign_in = new \user\sign_in();
        $sign_in->set_html_username_name('email');
        $sign_in->set_html_password_name('password');

        $username = $sign_in->login();
        
        // If everything checks out, set session and reload site 
		\user\session::set($username);
        redirect('/');
	}
}
