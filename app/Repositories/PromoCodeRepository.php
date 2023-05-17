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

    /**
     * @param string $code
     * @return bool
     */
    public function checkCodeUnique(string $code)
    {
        return $this->model->where('code', '=', $code)->count() == 0;
    }
}
