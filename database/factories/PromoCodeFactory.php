<?php

namespace Database\Factories;

use App\Models\PromoCode;
use App\Utils\PromoCodesTypesUtil;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PromoCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PromoCode::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'code' => 'FLM-' . Str::random(4),
            'type' => PromoCodesTypesUtil::VALUE,
            'value' => 10,
            'expiry_date' => Carbon::today()->toDateString(),
            'max_no_of_usages' => rand(2, 5),
            'max_no_of_usages_per_user' => 3,
            'allowed_users' => json_encode([1,2,3])
        ];
    }
}
