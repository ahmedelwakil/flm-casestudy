<?php

namespace App\Services;

use App\Repositories\PromoCodeRepository;

class PromoCodeService extends BaseService
{
    /**
     * @var PromoCodeRepository
     */
    protected $repository;

    /**
     * PromoCodeService constructor.
     * @param PromoCodeRepository $promoCodeRepository
     */
    public function __construct(PromoCodeRepository $promoCodeRepository)
    {
        parent::__construct($promoCodeRepository);
    }
}
