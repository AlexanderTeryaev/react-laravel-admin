<?php

namespace App\GraphQL\Mutations\User;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class VerifyEmail
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $email = $args['email'];
        $token = $args['token'];

        $user = User::where('email', '=', $email)->first();
        if ($user == null)
            return false;

        $true_token = md5($user->current_group_portal . '-' . $user->id . '-' . $email);
        if ($token == $true_token) {
            $user->update(['email_verified_at' => now()]);
            return true;
        }

        return false;
    }
}
