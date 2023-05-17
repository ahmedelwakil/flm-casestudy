<?php

namespace App\Validators\PromoCodes;

use Carbon\Carbon;

class PromoCodeExpiryValidator extends PromoCodeBaseValidator
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        $today = Carbon::today();
        $expiryDate = Carbon::parse($this->promoCode->expiry_date);
        return $expiryDate->isAfter($today);
    }

    /**
     * @return string
     */
    public function getFailureMessage(): string
    {
        return 'Promo Code is Expired';
    }
}
