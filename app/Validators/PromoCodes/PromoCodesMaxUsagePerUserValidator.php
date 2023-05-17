<?php

namespace App\Validators\PromoCodes;

use App\Repositories\PromoCodesUsageRepository;
use Illuminate\Support\Facades\Auth;

class PromoCodesMaxUsagePerUserValidator extends PromoCodeBaseValidator
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        $promoCodeUsageRepository = resolve(PromoCodesUsageRepository::class);
        $numberOfUsagesPerUser = $promoCodeUsageRepository->getUsagesPerUserCount($this->promoCode->id, Auth::user()->id);
        return $numberOfUsagesPerUser < $this->promoCode->max_no_of_usages_per_user;
    }

    /**
     * @return string
     */
    public function getFailureMessage(): string
    {
        return 'Max Number of Usages Per User Exceeded';
    }
}
