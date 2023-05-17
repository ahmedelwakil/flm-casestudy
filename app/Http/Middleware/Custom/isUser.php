<?php

namespace App\Http\Middleware\Custom;

use App\Exceptions\UnauthorizedAction;
use App\Models\User;
use App\Utils\UserTypeUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isUser
{
    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws UnauthorizedAction
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->type == UserTypeUtil::USER)
            return $next($request);

        throw new UnauthorizedAction();
    }
}
