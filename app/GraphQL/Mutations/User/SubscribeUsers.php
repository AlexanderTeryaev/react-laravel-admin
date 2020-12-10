<?php

namespace App\GraphQL\Mutations\User;

use App\Exceptions\ResolverException;
use App\Quizz;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SubscribeUsers
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
        $user = Request::get('user');
        $u_ids = (isset($args['user_ids'])) ? $args['user_ids'] : $user->manageable_group->users()->pluck('users.id');

        foreach ($u_ids as $user_id){
            $u = User::find($user_id);
            if (!$u->isInGroup($user->manageable_group->id))
                throw new ResolverException(
                    "User #{$u->id} is not in the group #{$user->manageable_group->id}",
                    'The user is not in the group, so he cannot be subscribed to the quizz.'
                );
            foreach ($args['quizz_ids'] as $quizz_id)
            {
                $quizz = Quizz::find($quizz_id);
                if ($quizz && !$u->isSubscribed($quizz->id))
                    $u->quizzes()->syncWithoutDetaching([$quizz->id => ['group_id' => $user->manageable_group->id]]);
            }
        }
        return $u_ids;
    }
}
