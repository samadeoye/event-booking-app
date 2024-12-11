<?php
namespace KpakpandoEventsBooking\Auth;

use Exception;
use KpakpandoEventsBooking\Crud\Crud;

class Login
{
    static $table = DEF_TBL_ADMINS;
    public static function loginAdmin()
    {
        $email = trim($_REQUEST['email']);
        $password = trim($_REQUEST['password']);

        //check if a user exists with the email
        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'id, first_name, last_name, fullname, email, password, status',
                'where' => [
                    'email' => $email,
                    'deleted' => 0
                ]
            ]
        );
        if ($rs)
        {
            if ($rs['status'] != 1)
            {
                throw new Exception('Your account is disabled. Please contact the admin.');
            }
            elseif (md5($password) != $rs['password'])
            {
                throw new Exception('Email or Password is incorrect');
            }
            else
            {
                //login
                $_SESSION['admin'] = $rs;
            }
        }
        else
        {
            throw new Exception('User with this email does not exist');
        }
    }
}