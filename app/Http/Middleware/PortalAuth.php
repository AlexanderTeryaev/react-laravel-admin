<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthException;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PortalAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @throws \App\Exceptions\AuthException
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check())
            throw new AuthException(
                'You are not allowed to perform this action',
                'You must be authenticated by jwt token and have rights on a group to do this operation'
            );

        $user = Auth::user();

        if ($user->manageableGroups()->count() == 0)
        {
            Log::warning('Login with any manageable group', $user->debugInfos());
            Auth::logout();
            throw new AuthException(
                'You are not allowed to perform this action',
                'No manageable groups'
            );
        }

        if (!$user->manageable_group)
        {
            Log::warning('Login with null manageable group', $user->debugInfos());
            $user->update(['current_group_portal' => $user->manageableGroups()->first()->id]);

            throw new AuthException(
                'Something went wrong',
                'Please retry'
            );
        }

        return $next($request);
    }
}
