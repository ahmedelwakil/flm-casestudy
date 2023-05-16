<?php

namespace App\Utils;

class UserTypeUtil
{
    CONST ADMIN = 'admin';
    CONST USER = 'user';

    public static function getUserTypes()
    {
        return [
            self::ADMIN,
            self::USER
        ];
    }
}
