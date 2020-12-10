<?php

namespace App\Http\Middleware;

use App\Exceptions\AuthException;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthUserGql
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @throws \Exception
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $device_id = $request->headers->get('x-mrmld-device-id');
        $curr_os = $request->headers->get('x-mrmld-client-os');
        $curr_app_version = $request->headers->get('x-mrmld-app-version');
        $curr_app_lang = $request->headers->get('x-mrmld-app-lang');
        $ip = Request::ip();

        $v = Validator::make($request->headers->all(), [
            'x-mrmld-client-os.*' => [Rule::in(['ios', 'android'])],
            'x-mrmld-app-version.*' => 'string|min:1|max:15',
            'x-mrmld-app-lang.*' => 'string|min:2|max:15'
        ]);

        if ($v->fails())
            throw new AuthException(
                'Incorrect headers format',
                'Please check validation_errors row for more details',
                $v->errors()
            );

        if (Auth::check()) {
            $user = Auth::user();
            $user->update([
                'last_ip' => $ip,
                'curr_os' => $curr_os ?? 'portal',
                'curr_app_version' => $curr_app_version,
                'curr_app_lang' => $curr_app_lang
            ]);
        }
        ### ALL THE NEXT BLOCK WILL DISAPPEAR !!!
        elseif ($device_id && !$request->hasHeader('Authorization')) {
            $v = Validator::make($request->headers->all(), [
                'x-mrmld-device-id.*' => 'required|min:8|max:254|alpha_dash',
            ]);
            if ($v->fails())
                throw new AuthException(
                    'Incorrect headers format',
                    'Please check validation_errors row for more details',
                    $v->errors()
                );

            $user = User::where('device_id', '=', $device_id)->first();
            $curr_os = ($curr_os) ? $curr_os : 'n/a';

            if ($user) {
                $user->update([
                    'last_ip' => $ip,
                    'curr_os' => $curr_os,
                    'curr_app_version' => $curr_app_version,
                    'curr_app_lang' => $curr_app_lang
                ]);
            } else {
                try {
                    $user = User::create([
                        'device_id' => $device_id,
                        'current_group' => 1,
                        'last_ip' => $ip,
                        'curr_os' => $curr_os,
                        'curr_app_version' => $curr_app_version,
                        'curr_app_lang' => $curr_app_lang
                    ]);
                    $user->usernameGenerator();
                    $user->addInGroup(1, 'default');
                } catch (\Exception $e) {
                    if (Str::containsAll($e->getMessage(), ['SQLSTATE[23000]', '1062 Duplicate', 'users_device_id_unique']))
                        $user = User::where('device_id', '=', $device_id)->first();
                    else
                        throw $e;
                }
            }

        } elseif ($request->headers->get('Authorization')) {
            throw new AuthException(
                'Invalid token',
                'The token is invalid, it is either expired or corrupted.'
            );
        } else {
            throw new AuthException(
                'No authentication method find',
                'You must provide device-id or authorization token'
            );
        }

        $request->attributes->add(['user' => $user]);
        return $next($request);
    }
}
