<?php

namespace App\Http\Controllers\AdminApiControllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login
     *
     * @param Request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $token = auth('user')->attempt($request->only('email', 'password'));
        if ($token)
        {
            $user = User::where('email', '=', $request->get('email'))->first();

            if ($user->email_verified_at == null)
                abort(403, 'Email address has not been verified');

            if ($user->hasRole('admin'))
            {
                if ($user->current_group_portal == null)
                    $user->update(['current_group_portal' => $user->manageableGroups()->first()->id]);

                return response()->json([
                    'token' => auth('user')->claims([
                        'platform' => 'bo',
                        'is_admin' => $user->hasRole('admin')
                    ])->attempt($request->only('email', 'password'))
                ], 200);
            }
            Log::warning("{$user->email} tried to log in to BO");
            throw ValidationException::withMessages([
                'email' => ["You must be admin"],
            ]);
        }
        throw ValidationException::withMessages([
            'email' => ["These credentials do not match our records."],
        ]);
    }

    /**
     * Logout
     *
     * @param Request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'status' => 'success'
        ]);
    }
}
