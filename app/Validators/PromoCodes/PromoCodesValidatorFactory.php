<?php

namespace App\Validators\PromoCodes;

use App\Exceptions\LogicalException;
use App\Models\PromoCode;

class PromoCodesValidatorFactory
{
    CONST VALIDATORS = [
        PromoCodeExpiryValidator::class,
        PromoCodesUserAvailabilityValidator::class,
        PromoCodesMaxUsageValidator::class,
        PromoCodesMaxUsagePerUserValidator::class
    ];

    /**
     * @param PromoCode $promoCode
     * @return bool
     * @throws LogicalException
     */
    public static function validate(PromoCode $promoCode)
    {
        foreach (self::VALIDATORS as $validator) {
            $validatorClass = new $validator($promoCode);
            if (!$isValid = $validatorClass->validate())
                throw new LogicalException($validatorClass->getFailureMessage());
        }
        return true;
    }
}
