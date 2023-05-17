<?php

namespace App\Http\Controllers;

use App\Exceptions\LogicalException;
use App\Services\PromoCodeService;
use App\Units\PromoCodeCreationUnit;
use App\Utils\HttpStatusCodeUtil;
use App\Utils\PromoCodesTypesUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class PromoCodeController extends BaseController
{
    /**
     * @var PromoCodeService
     */
    protected $service;

    /**
     * PromoCodeController constructor.
     * @param PromoCodeService $promoCodeService
     */
    public function __construct(PromoCodeService $promoCodeService)
    {
        parent::__construct($promoCodeService);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Throwable
     */
    public function generatePromoCode(Request $request)
    {
        $validatedData = $this->validate($request, [
            'type' => 'required|string|in:' . implode(',', PromoCodesTypesUtil::getPromoCodesTypes()),
            'value' => 'required|numeric',
            'expiry_date' => 'required|date',
            'max_no_of_usages' => 'required|numeric',
            'max_no_of_usages_per_user' => 'required|numeric',
            'allowed_users' => 'sometimes|array',
            'allowed_users.*' => 'numeric|exists:users,id'
        ]);

        $promoCodeCreationUnit = new PromoCodeCreationUnit(
            $validatedData['type'],
            $validatedData['value'],
            $validatedData['expiry_date'],
            $validatedData['max_no_of_usages'],
            $validatedData['max_no_of_usages_per_user'],
            $validatedData['allowed_users'] ?? null
        );

        $promoCode = $this->service->add($promoCodeCreationUnit->toArray());
        return $this->response(['promo_code' => $promoCode->code], HttpStatusCodeUtil::OK, 'Promo Code Created Successfully');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws LogicalException
     * @throws Throwable
     * @throws ValidationException
     */
    public function validatePromoCode(Request $request)
    {
        $validatedData = $this->validate($request, [
            'promo_code' => 'required|string|exists:promo_codes,code',
            'price' => 'required|numeric'
        ]);

        $promoCodeUsage = $this->service->applyPromoCode($validatedData['promo_code'], $validatedData['price']);
        $payload = [
            'price' => $promoCodeUsage->price,
            'promocode_discounted_amount' => $promoCodeUsage->discount,
            'final_price' => $promoCodeUsage->final_price
        ];
        return $this->response($payload, HttpStatusCodeUtil::OK, 'Promo Code Applied Successfully');
    }
}
