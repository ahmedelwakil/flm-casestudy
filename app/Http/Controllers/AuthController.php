<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedAction;
use App\Models\User;
use App\Services\AuthService;
use App\Utils\HttpStatusCodeUtil;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    /**
     * @var AuthService
     */
    protected $service;

    /**
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        parent::__construct($authService);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
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

        return $this->response($payload, HttpStatusCodeUtil::OK, 'Login Successfully');
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        $token = Auth::getToken();
        $this->service->invalidateToken($token);
        Auth::logout();
        return $this->response(null, HttpStatusCodeUtil::OK, "Logged Out Successfully");
    }

    /**
     * @return JsonResponse
     * @throws UnauthorizedAction
     */
    public function refresh(): JsonResponse
    {
        $tokenPayload = auth()->payload()->toArray();
        if (!isset($tokenPayload['refresh']) && !$tokenPayload['refresh']) {
            throw new UnauthorizedAction('Invalid Refresh Token');
        }

        $relatedToken = $tokenPayload['relatedToken'];
        $this->service->invalidateToken($relatedToken);

        /** @var User $user */
        $user = Auth::user();
        $accessToken = Auth::login($user);
        $refreshToken = $this->service->createRefreshToken($user, $accessToken);

        $payload = [
            'tokens' => [
                'accessToken' => $accessToken,
                'refreshToken' => $refreshToken,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60
            ]
        ];
        return $this->response($payload, HttpStatusCodeUtil::OK, "Refresh Successfully");
    }
}
