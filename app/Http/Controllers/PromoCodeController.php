<?php

namespace App\Http\Controllers;

use App\Services\PromoCodeService;
use Illuminate\Http\Request;

class PromoCodeController extends BaseController
{
    /**
     * @var PromoCodeService
     */
    protected $service;

    /**
     * PromoCodeController constructor.
     * @param PromoCodeService $promoCodeService
     */
    public function __construct(PromoCodeService $promoCodeService)
    {
        parent::__construct($promoCodeService);
    }

    /**
     * @param Request $request
     */
    public function generate(Request $request)
    {

    }

    /**
     * @param Request $request
     * @return array|void
     */
    public function validate(Request $request)
    {

    }
}
