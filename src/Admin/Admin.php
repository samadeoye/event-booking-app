<?php
namespace KpakpandoEventsBooking\Admin;

use Exception;
use KpakpandoEventsBooking\Crud\Crud;
use KpakpandoEventsBooking\Mailer\Mailer;

class Admin
{
    static $table = DEF_TBL_ADMINS;
    static $tablePasswordReset = DEF_TBL_PASSWORD_RESET;
    static $data = [];

    public static function checkIfAdminExists($field, $value)
    {
        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'id',
                'where' => [
                    $field => $value
                ]
            ]
        );
        if ($rs)
        {
            return true;
        }
        return false;
    }
    public static function changePassword()
    {
        global $userId;

        $currentPassword = trim($_REQUEST['currentPassword']);
        $newPassword = trim($_REQUEST['newPassword']);
        $confirmPassword = trim($_REQUEST['confirmPassword']);
        $userPassword = $_SESSION['admin']['password'];

        if ($newPassword != $confirmPassword)
        {
            throw new Exception('Passwords do not match');
        }
        elseif ($userPassword != md5($currentPassword))
        {
            throw new Exception('Old password is incorrect');
        }
        else
        {
            $newPassword = md5($newPassword);
            $data = [
                'password' => $newPassword,
                'mdate' => time()
            ];
            $update = Crud::update(
                self::$table,
                $data,
                [
                    'id' => $userId
                ]
            );
            if ($update)
            {
                $rs = $_SESSION['admin'];
                $rs = array_merge($rs, ['password' => $newPassword]);
                $_SESSION['admin'] = $rs;
            }
        }
    }
    public static function updateAdmin()
    {
        global $userId;

        $fname = stringToTitle(trim($_REQUEST['fname']));
        $lname = stringToTitle(trim($_REQUEST['lname']));

        $data = [
            'first_name' => $fname
            , 'last_name' => $lname
            , 'fullname' => "{$fname} {$lname}"
            , 'mdate' => getCurrentDate()
        ];
        $update = Crud::update(
            self::$table,
            $data,
            [
                'id' => $userId
            ]
        );
        if ($update)
        {
            $rs = $_SESSION['admin'];
            $rs = array_merge($rs, $data);
            $_SESSION['admin'] = $rs;

            $data = [
                'status' => true,
                'data' => $_SESSION['admin']
            ];
            self::$data = $data;
        }
    }

    public static function verifyEmailForPasswordReset()
    {
        $email = strtolower(trim($_REQUEST['email']));

        $rs = Crud::select(
            self::$table,
            [
                'columns' => 'first_name, last_name, fullname',
                'where' => [
                    'email' => $email
                ]
            ]
        );
        if ($rs)
        {
            //send password reset emaisl
            $id = getNewId();
            $name = stringToTitle($rs['fullname']);
            $siteName = SITE_NAME;
            $siteRootPath = DEF_FULL_ROOT_PATH;

            $firstName = stringToTitle($rs['first_name']);
            $passResetLink = <<<EOQ
            <a href="{$siteRootPath}/resetpassword?token={$id}">Reset Password</a>
EOQ;

            $body = <<<EOQ
Dear {$firstName},<br><br>
Use the link below to complete your password reset on {$siteName}.<br><br>
{$passResetLink}<br>
EOQ;

            $arParams = [
                'mailTo' => $email,
                'toName' => $name,
                'mailFrom' => SITE_EMAIL,
                'fromName' => $siteName,
                'subject' => "Password Reset on {$siteName}",
                'body' => $body
            ];
            Mailer::sendMail($arParams);
            if (Mailer::$isSent)
            {
                $data = [
                    'id' => $id
                    , 'email' => $email
                    , 'cdate' => getCurrentDate()
                ];
                Crud::insert(
                    self::$tablePasswordReset
                    , $data
                );
            }
            else
            {
                throw new Exception('An error occured. Please try again.');
            }
        }
        else
        {
            throw new Exception('This email does not exist on the system');
        }
    }

    public static function resetPassword()
    {
        $token = trim($_REQUEST['token']);
        $password = trim($_REQUEST['password']);
        $passwordConfirm = trim($_REQUEST['passwordConfirm']);

        if ($password != $passwordConfirm)
        {
            throw new Exception('Passwords do not match!');
        }

        $rs = Crud::select(
            self::$tablePasswordReset,
            [
                'columns' => 'email',
                'where' => [
                    'id' => $token
                ],
                'order' => 'cdate DESC',
                'limit' => 1
            ]
        );

        if ($rs)
        {
            Crud::update(
                self::$table
                , ['password' => md5($password)]
                , ['email' => $rs['email']]
            );
            //delete password reset log
            Crud::delete(
                self::$tablePasswordReset
                , ['email' => $rs['email']]
            );
        }
        else
        {
            throw new Exception('Token is invalid. Please click the link from your email.');
        }
    }
}