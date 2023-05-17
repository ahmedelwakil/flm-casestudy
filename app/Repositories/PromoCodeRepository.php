<?php

namespace App\Repositories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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

    /**
     * @param string $promo_code
     * @return Builder|Model|object|null
     */
    public function findByCode(string $promo_code)
    {
        return $this->model->where('code', '=', $promo_code)->first();
    }
}
