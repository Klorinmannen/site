<?php
namespace register;

class action
{       
    public static function signup()
    {
        $signup = new \user\signup();
        $sign_in->set_html_email_name('email');
        $sign_in->set_html_username_name('username');
        $sign_in->set_html_password_name('password');

        try {
            $new_user_inputs = $signup->get_inputs();
        } catch ($error) {
            return $error->getMessage();
        }

        $new_user = [ 'Email' => $new_user_inputs['email'],
                      'Username' => $new_user_inputs['username'],
                      'Password' => $new_user_inputs['password'] ];
        
        return \user\table::insert($new_user);
    }
}
