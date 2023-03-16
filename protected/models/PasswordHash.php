<?php

class PasswordHash
{
    public static function generate($pass)
    {
        $options = [
            'cost' => 12,
        ];

        $pass = password_hash($pass, PASSWORD_BCRYPT, $options);
        return Responses::getOk(['password' => $pass]);
    }
    public static function verify($pass, $confirmPass)
    {
        if(password_verify($pass, $confirmPass)) {
            return Responses::getOk();
        }else{
            return Responses::getError();
        }
    }
}
