<?php

namespace App\GraphQL\Mutations\User;

use App\EmailAuthentication;
use App\Exceptions\ResolverException;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RegisterWithEmail
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @throws ResolverException
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $email = $args['email'];
        $code = $args['code'];

        $ea = EmailAuthentication::where('type', 'register')->where('email', $email)->where('code', $code)->first();

        if ($ea)
        {
            if ($ea->verified_at != null)
                throw new ResolverException(
                    __('Used code'),
                    __('The code you entered has already been used, please request a new one.')
                );
            if ($ea->created_at->isBefore(now()->subMinutes(15)))
                throw new ResolverException(
                    __('The entered code has expired'),
                    __('You must enter the code received before 15 minutes')
                );

            $ea->update([
                'verified_at' => now()
            ]);

            if (User::where('email', $email)->exists())
                throw new ResolverException(
                    __('Email address already in use'),
                    __('This email address is already in use, you should try to login with it.')
                );

            $device_id = Request::header('x-mrmld-device-id');
            if ($device_id && ($user = User::where('device_id', '=', $device_id)->first()) && $user->email == null)
            {
                $user->update([
                    'email' => $email,
                    'email_verified_at' => now()
                ]);
            } else {
                $user = User::create([
                    'device_id' => $device_id,
                    'email' => $email,
                    'current_group' => 1,
                    'last_ip' => Request::ip(),
                    'email_verified_at' => now()
                ]);
                $user->usernameGenerator();
                $user->addInGroup(1, 'default');
            }

            return [
                'token' => Auth::setTTL(60 * 24 * 365)->login($user),
                'viewer' => $user
            ];
        }
        throw new ResolverException(
            __('Invalid code'),
            __('The code you entered is invalid, please check the code and type it in again.')
        );
    }
}
