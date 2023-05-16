<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends BaseController
{
    protected $service;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->service = $authService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        $accessToken = $this->service->authenticate($credentials);

        /** @var User $user */
        $user = Auth::user();
        $refreshToken = $this->service->createRefreshToken($user, $accessToken);

        $payload = [
            'tokens' => [
                'accessToken' => $accessToken,
                'refreshToken' => $refreshToken,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ],
            'user' => $user->toArray()
        ];

        return $this->response($payload, 200, 'Login Successful');
    }
}
