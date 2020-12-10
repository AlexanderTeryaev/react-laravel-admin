<?php

namespace App\GraphQL\Mutations\User;

use App\Exceptions\ResolverException;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Login
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @throws ResolverException
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $token = Auth::attempt($args);
        if ($token)
        {
            $user = User::where('email', '=', $args['email'])->first();

            if ($user->email_verified_at == null)
                throw new ResolverException(
                    'Email address has not been verified',
                    'Please confirm your email address by clicking on the button in the email we sent you'
                );

            if ($user->manageableGroups()->count() > 0)
            {
                if ($user->current_group_portal == null)
                    $user->update(['current_group_portal' => $user->manageableGroups()->first()->id]);

                return [
                    'token' => Auth::claims([
                        'is_portal' => true,
                        'is_admin' => $user->hasRole('admin')
                    ])->attempt($args),
                    'viewer' => $user
                ];
            }
        }
        return null;
    }
}
