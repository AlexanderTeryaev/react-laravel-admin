<?php

namespace App\GraphQL\Mutations\User;

use App\EmailAuthentication;
use App\Exceptions\ResolverException;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use function Complex\negative;

class LoginWithEmail
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

        $ea = EmailAuthentication::where('type', 'login')->where('email', $email)->where('code', $code)->first();

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

            $user = User::where('email', $email)->first();
            if (!$user)
            {
                Log::error("Error when user {$email} LoginWithEmail with a good code");
                throw new ResolverException(
                    __('Internal server error'),
                    __('Please be patient, we have been notified of the issue, we are doing our best to resolve the issue')
                );
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
