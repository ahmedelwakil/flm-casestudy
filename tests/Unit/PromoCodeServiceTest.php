<?php

namespace Tests\Unit;

use App\Models\PromoCode;
use App\Services\PromoCodeService;
use App\Utils\PromoCodesTypesUtil;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoCodeServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @var PromoCodeService */
    protected $service;
    public function __construct()
    {
        parent::__construct();
        $this->service = resolve(PromoCodeService::class);
    }

    public function testCalculatePromoCodeDataFunctionWithValue()
    {
        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['type' => PromoCodesTypesUtil::VALUE, 'value' => 10]);
        $price = 150;

        $return = $this->service->calculatePromoCodeData($promoCode, $price);
        $this->assertEquals(140, $return['finalPrice']);
        $this->assertEquals(10, $return['discount']);
    }

    public function testCalculatePromoCodeDataFunctionWithPercentage()
    {
        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['type' => PromoCodesTypesUtil::PERCENTAGE, 'value' => 10]);
        $price = 150;

        $return = $this->service->calculatePromoCodeData($promoCode, $price);
        $this->assertEquals(135, $return['finalPrice']);
        $this->assertEquals(15, $return['discount']);
    }
}
