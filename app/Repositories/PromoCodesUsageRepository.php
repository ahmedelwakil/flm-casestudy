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
}
