<?php

namespace App\Repositories;

use App\Models\PromoCodesUsage;

class PromoCodesUsageRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return PromoCodesUsage::class;
    }

    /**
     * @param int $promoCodeId
     * @return int
     */
    public function getUsagesCount(int $promoCodeId)
    {
        return $this->model->where('promo_code_id', '=', $promoCodeId)->count();
    }

    /**
     * @param int $promoCodeId
     * @param int $userId
     * @return int
     */
    public function getUsagesPerUserCount(int $promoCodeId, int $userId)
    {
        return $this->model
            ->where('promo_code_id', '=', $promoCodeId)
            ->where('user_id', '=', $userId)
            ->count();
    }
}
