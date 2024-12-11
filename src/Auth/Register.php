<?php
namespace KpakpandoEventsBooking\Auth;

use Exception;
use KpakpandoEventsBooking\Crud\Crud;
use KpakpandoEventsBooking\Admin\Admin;

class Register
{
    static $table = DEF_TBL_ADMINS;
    public static function registerAdmin()
    {
        $fname = stringToTitle(trim($_REQUEST['fname']));
        $lname = stringToTitle(trim($_REQUEST['lname']));
        $email = strtolower(trim($_REQUEST['email']));
        $password1 = trim($_REQUEST['password1']);
        $password2 = trim($_REQUEST['password2']);

        if ($password1 != $password2)
        {
            throw new Exception('Passwords do not match');
        }

        //check if a user exists with the same email
        if (Admin::checkIfAdminExists('email', $email))
        {
            throw new Exception('A user already exists with this email');
        }

        //proceed to register
        $id = getNewId();
        $data = [
            'id' => $id
            , 'first_name' => $fname
            , 'last_name' => $lname
            , 'fullname' => "{$fname} {$lname}"
            , 'email' => $email
            , 'password' => md5($password1)
            , 'cdate' => getCurrentDate()
        ];
        if (Crud::insert(self::$table, $data))
        {
            $rs = Crud::select(
                self::$table,
                [
                    'columns' => 'id, first_name, last_name, email, password, status',
                    'where' => [
                        'id' => $id
                    ]
                ]
            );
            $_SESSION['admin'] = $rs;
        }
        else
        {
            throw new Exception('An error occured');
        }
    }
}