<?php

namespace App\Utils;

class PromoCodesTypes
{
    CONST VALUE = 'value';
    CONST PERCENTAGE = 'percentage';

    public static function getPromoCodesTypes()
    {
        return [
            self::VALUE,
            self::PERCENTAGE
        ];
    }
}
