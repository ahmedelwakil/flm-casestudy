<?php

namespace App\Units;

use Illuminate\Support\Facades\Auth;

class PromoCodeUsageCreationUnit implements Unit
{
    protected $userId;
    protected $promoCodeId;
    protected $price;
    protected $discount;
    protected $finalPrice;

    /**
     * PromoCodeUsageCreationUnit constructor.
     * @param int $promoCodeId
     * @param float $price
     * @param float $discount
     * @param float $finalPrice
     */
    public function __construct(int $promoCodeId, float $price, float $discount, float $finalPrice)
    {
        $this->userId = Auth::user()->id;
        $this->promoCodeId = $promoCodeId;
        $this->price = $price;
        $this->discount = $discount;
        $this->finalPrice = $finalPrice;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'promo_code_id' => $this->promoCodeId,
            'price' => $this->price,
            'discount' => $this->discount,
            'final_price' => $this->finalPrice
        ];
    }
}
