<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedAction;
use App\Utils\HttpStatusCodeUtil;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null)
    {
        try {
            if ($this->auth->guard($guard)->guest()) {
                throw new UnauthorizedAction();
            }

            $guards = config("auth.guards");
            foreach ($guards as $guard => $value) {
                if ($this->auth->guard($guard)->check()) {
                    return $next($request);
                }
            }

            $message = null;
            Auth::getClaim('exp');
        } catch (TokenExpiredException $e) {
            $message = [
                'errors' => [
                    'message' => 'Token Expired'
                ]
            ];
        } catch (TokenBlacklistedException $e) {
            $message = [
                'errors' => [
                    'message' => 'Token Blacklisted'
                ]
            ];
        } catch (TokenInvalidException $e) {
            $message = [
                'errors' => [
                    'message' => 'Token Invalid'
                ]
            ];
        } catch (JWTException $e) {
            $message = [
                'errors' => [
                    'message' => 'Token Absent'
                ]
            ];
        } catch (\Exception $ex) {
            $message = [
                'errors' => [
                    'message' => 'Unauthorized'
                ]
            ];
        }

        return response()->json(["payload" => $message], HttpStatusCodeUtil::UNAUTHORIZED, [], JSON_INVALID_UTF8_IGNORE);
    }
}
