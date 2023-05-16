<?php

namespace App\Repositories;

use App\Models\PromoCode;

class PromoCodeRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return PromoCode::class;
    }
}
