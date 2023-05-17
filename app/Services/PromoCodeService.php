<?php

namespace App\Services;

use App\Exceptions\LogicalException;
use App\Models\PromoCode;
use App\Repositories\PromoCodeRepository;
use App\Units\PromoCodeUsageCreationUnit;
use App\Utils\PromoCodesTypesUtil;
use App\Validators\PromoCodes\PromoCodesValidatorFactory;
use Illuminate\Support\Str;
use Throwable;

class PromoCodeService extends BaseService
{
    /**
     * @var PromoCodeRepository
     */
    protected $repository;

    /**
     * @var PromoCodeUsageService
     */
    protected $promoCodeUsageService;

    /**
     * PromoCodeService constructor.
     * @param PromoCodeRepository $promoCodeRepository
     * @param PromoCodeUsageService $promoCodeUsageService
     */
    public function __construct(PromoCodeRepository $promoCodeRepository, PromoCodeUsageService $promoCodeUsageService)
    {
        parent::__construct($promoCodeRepository);
        $this->promoCodeUsageService = $promoCodeUsageService;
    }

    /**
     * @param mixed $data
     * @return mixed
     * @throws Throwable
     */
    public function add($data)
    {
        do {
            $codeIsUnique = $this->repository->checkCodeUnique($data['code']);
            if (!$codeIsUnique)
                $data['code'] = 'FLM-' . Str::random(4);
        } while(!$codeIsUnique);

        return parent::add($data);
    }

    /**
     * @param string $promo_code
     * @param float $price
     * @return mixed
     * @throws LogicalException
     * @throws Throwable
     */
    public function applyPromoCode(string $promo_code, float $price)
    {
        /** @var PromoCode $promoCode */
        $promoCode = $this->repository->findByCode($promo_code);
        $this->validatePromoCode($promoCode);
        $calculationData = $this->calculatePromoCodeData($promoCode, $price);
        $promoCodeUsageCreationUnit = new PromoCodeUsageCreationUnit(
            $promoCode->id,
            $price,
            $calculationData['discount'],
            $calculationData['finalPrice']
        );
        return $this->promoCodeUsageService->add($promoCodeUsageCreationUnit->toArray());
    }

    /**
     * @param PromoCode $promoCode
     * @return bool
     * @throws LogicalException
     */
    public function validatePromoCode(PromoCode $promoCode)
    {
        return PromoCodesValidatorFactory::validate($promoCode);
    }

    public function calculatePromoCodeData(PromoCode $promoCode, $price)
    {
        $discount = 0;
        switch ($promoCode->type) {
            case PromoCodesTypesUtil::PERCENTAGE:
                $discount = $price * $promoCode->value / 100;
                break;
            case PromoCodesTypesUtil::VALUE:
                $discount = $promoCode->value;
                break;
            default:
                break;
        }
        $finalPrice = $price - $discount;
        return compact('discount', 'finalPrice');
    }
}
