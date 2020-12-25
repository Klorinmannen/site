<?php
namespace register;

class action
{       
    public static function signup()
    {
        $signup = new \user\signup();
        $signup->set_html_email_name('email');
        $signup->set_html_username_name('username');
        $signup->set_html_password_name('password');

        $new_user_inputs = $signup->get_inputs();
        $new_user = [ 'Email' => $new_user_inputs['email'],
                      'Username' => $new_user_inputs['username'],
                      'Password' => $new_user_inputs['password'],
                      'Active' => 0 ];
        
        \user\model::insert($new_user);
    }
}
