<?php

namespace Tests\Unit;

use App\Exceptions\LogicalException;
use App\Models\PromoCode;
use App\Models\PromoCodesUsage;
use App\Models\User;
use App\Services\PromoCodeService;
use App\Utils\PromoCodesTypesUtil;
use Carbon\Carbon;
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

    public function testValidatePromoCodeExpired()
    {
        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['expiry_date' => Carbon::yesterday()->toDateString()]);
        $this->expectException(LogicalException::class);
        $this->service->validatePromoCode($promoCode);
    }

    public function testValidatePromoCodeNotExpired()
    {
        /** @var User $user */
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

        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['allowed_users' => json_encode([rand($user->id + 1, 100)])]);

        $this->actingAs($user);
        $this->service->validatePromoCode($promoCode);
    }

    public function testValidatePromoCodeUserAvailable()
    {
        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['allowed_users' => json_encode([$user->id])]);

        $this->actingAs($user);
        $this->service->validatePromoCode($promoCode);
        $this->assertTrue(true);
    }

    public function testPromoCodeExceededMaxUsages()
    {
        $this->expectException(LogicalException::class);

        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['max_no_of_usages' => 3, 'allowed_users' => json_encode([$user->id])]);

        PromoCodesUsage::factory(3)->create(['user_id' => $user->id, 'promo_code_id' => $promoCode->id]);

        $this->actingAs($user);
        $this->service->validatePromoCode($promoCode);
    }

    public function testPromoCodeNotExceededMaxUsages()
    {
        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['max_no_of_usages' => 3, 'allowed_users' => json_encode([$user->id])]);

        PromoCodesUsage::factory(2)->create(['user_id' => $user->id, 'promo_code_id' => $promoCode->id]);

        $this->actingAs($user);
        $this->service->validatePromoCode($promoCode);
        $this->assertTrue(true);
    }

    public function testPromoCodeExceededMaxUsagesForUser()
    {
        $this->expectException(LogicalException::class);

        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['max_no_of_usages' => 10, 'max_no_of_usages_per_user' => 2, 'allowed_users' => json_encode([$user->id])]);

        PromoCodesUsage::factory(2)->create(['user_id' => $user->id, 'promo_code_id' => $promoCode->id]);

        $this->actingAs($user);
        $this->service->validatePromoCode($promoCode);
    }

    public function testPromoCodeNotExceededMaxUsagesForUser()
    {
        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne(['max_no_of_usages' => 10, 'max_no_of_usages_per_user' => 2, 'allowed_users' => json_encode([$user->id])]);

        PromoCodesUsage::factory(1)->create(['user_id' => $user->id, 'promo_code_id' => $promoCode->id]);

        $this->actingAs($user);
        $this->service->validatePromoCode($promoCode);
        $this->assertTrue(true);
    }

    public function testApplyPromoCodeValue()
    {
        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne([
            'type' => PromoCodesTypesUtil::VALUE,
            'value' => 10,
            'expiry_date' => Carbon::tomorrow()->toDateString(),
            'max_no_of_usages' => rand(2, 5),
            'max_no_of_usages_per_user' => 3,
            'allowed_users' => json_encode([$user->id])
        ]);

        $this->actingAs($user);
        $promoCodeUsage = $this->service->applyPromoCode($promoCode->code, 150);
        $this->assertDatabaseHas('promo_codes_usages', ['user_id' => $user->id, 'promo_code_id' => $promoCode->id]);
        $this->assertEquals(10, $promoCodeUsage->discount);
        $this->assertEquals(140, $promoCodeUsage->final_price);
    }

    public function testApplyPromoCodePercentage()
    {
        /** @var User $user */
        $user = User::factory()->createOne();

        /** @var PromoCode $promoCode */
        $promoCode = PromoCode::factory()->createOne([
            'type' => PromoCodesTypesUtil::PERCENTAGE,
            'value' => 10,
            'expiry_date' => Carbon::tomorrow()->toDateString(),
            'max_no_of_usages' => rand(2, 5),
            'max_no_of_usages_per_user' => 3,
            'allowed_users' => json_encode([$user->id])
        ]);

        $this->actingAs($user);
        $promoCodeUsage = $this->service->applyPromoCode($promoCode->code, 150);
        $this->assertDatabaseHas('promo_codes_usages', ['user_id' => $user->id, 'promo_code_id' => $promoCode->id]);
        $this->assertEquals(15, $promoCodeUsage->discount);
        $this->assertEquals(135, $promoCodeUsage->final_price);
    }
}
