<?php

namespace App\Services;

use App\Repositories\PromoCodeRepository;
use Illuminate\Support\Str;

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

    /**
     * @param mixed $data
     * @return mixed
     * @throws \Throwable
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
}
