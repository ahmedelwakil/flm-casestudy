<?php

namespace Tests\Unit;

use App\Exceptions\LogicalException;
use App\Models\PromoCode;
use App\Models\User;
use App\Services\PromoCodeService;
use App\Utils\PromoCodesTypesUtil;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
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

    public function testValidatePromoCodeExpired()
    {
        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['expiry_date' => Carbon::yesterday()->toDateString()]);
        $this->expectException(LogicalException::class);
        $this->service->validatePromoCode($promoCode);
    }

    public function testValidatePromoCodeNotExpired()
    {
        /** @var User $promoCode */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['allowed_users' => json_encode([$user->id])]);

        $this->actingAs($user);
        $this->service->validatePromoCode($promoCode);
        $this->assertTrue(true);
    }

    public function testValidatePromoCodeUserNotAvailable()
    {
        $this->expectException(LogicalException::class);

        /** @var User $promoCode */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['allowed_users' => json_encode([rand($user->id + 1, 100)])]);

        $this->actingAs($user);
        $this->service->validatePromoCode($promoCode);
    }

    public function testValidatePromoCodeUserAvailable()
    {
        /** @var User $promoCode */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['allowed_users' => json_encode([$user->id])]);

        $this->actingAs($user);
        $this->service->validatePromoCode($promoCode);
        $this->assertTrue(true);
    }
}
