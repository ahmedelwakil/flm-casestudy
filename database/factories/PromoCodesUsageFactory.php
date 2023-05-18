<?php

namespace Database\Factories;

use App\Models\PromoCodesUsage;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromoCodesUsageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromoCodesUsage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'promo_code_id' => 1,
            'price' => 20,
            'discount' => 10,
            'final_price' => 10
        ];
    }
}
