<?php

namespace App\Validators\PromoCodes;

use Illuminate\Support\Facades\Auth;

class PromoCodesUserAvailabilityValidator extends PromoCodeBaseValidator
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        $allowedUsers = json_decode($this->promoCode->allowed_users);
        return is_null($allowedUsers) || in_array(Auth::id(), $allowedUsers);
    }

    /**
     * @return string
     */
    public function getFailureMessage(): string
    {
        return 'Promo Code is Not Allowed For User';
    }
}
