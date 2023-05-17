<?php

namespace App\Validators\PromoCodes;

use App\Repositories\PromoCodesUsageRepository;

class PromoCodesMaxUsageValidator extends PromoCodeBaseValidator
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        $promoCodeUsageRepository = resolve(PromoCodesUsageRepository::class);
        $numberOfUsages = $promoCodeUsageRepository->getUsagesCount($this->promoCode->id);
        return $numberOfUsages < $this->promoCode->max_no_of_usages;
    }

    /**
     * @return string
     */
    public function getFailureMessage(): string
    {
        return 'Max Number of Usages Exceeded';
    }
}
