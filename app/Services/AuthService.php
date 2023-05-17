<?php

namespace App\Services;

use App\Exceptions\UnauthorizedAction;
use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;

class AuthService extends BaseService
{
    /**
     * Create a new controller instance.
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
        $this->repository = $repository;
    }

    /**
     * @param array $credentials
     * @return string
     * @throws Exception
     */
    public function authenticate(array $credentials)
    {
        if (!$accessToken = Auth::attempt($credentials)) {
            throw new UnauthorizedAction('Wrong Email Or Password');
        }
        return $accessToken;
    }

    /**
     * @param User $user
     * @param string $accessToken
     * @return mixed
     */
    public function createRefreshToken(User $user, string $accessToken)
    {
        $claims = ['refresh' => true, 'relatedToken' => $accessToken];
        return Auth::claims($claims)
            ->setTTL(config('jwt.refresh_ttl'))
            ->login($user);
    }

    /**
     * @param $token
     */
    public function invalidateToken($token)
    {
        Auth::refresh(true, true);
        Auth::setToken($token);
        Auth::invalidate();
    }
}
