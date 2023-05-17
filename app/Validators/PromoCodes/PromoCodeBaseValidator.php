<?php

namespace App\Validators\PromoCodes;

use App\Models\PromoCode;

abstract class PromoCodeBaseValidator
{
    /**
     * @var PromoCode
     */
    protected $promoCode;

    /**
     * Create a new controller instance.
     * @param PromoCode $promoCode
     */
    public function __construct(PromoCode $promoCode)
    {
        $this->promoCode = $promoCode;
    }

    /**
     * @return bool
     */
    abstract public function validate(): bool;

    /**
     * @return string
     */
    abstract public function getFailureMessage(): string;
}
