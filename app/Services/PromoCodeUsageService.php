<?php

namespace App\Services;

use App\Repositories\PromoCodesUsageRepository;

class PromoCodeUsageService extends BaseService
{
    /**
     * @var PromoCodesUsageRepository
     */
    protected $repository;

    /**
     * PromoCodeService constructor.
     * @param PromoCodesUsageRepository $promoCodesUsageRepository
     */
    public function __construct(PromoCodesUsageRepository $promoCodesUsageRepository)
    {
        parent::__construct($promoCodesUsageRepository);
    }
}
