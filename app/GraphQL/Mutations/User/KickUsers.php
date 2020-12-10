<?php

namespace App\GraphQL\Mutations\User;

use App\Exceptions\ResolverException;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class KickUsers
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
        $delete_data = (isset($args['delete_data'])) ? $args['delete_data'] : false;

        if ($user->manageable_group->id == 1) //Todo: get default group in config
            throw new ResolverException(
                'Forbidden to leave the main group',
                'It is forbidden to leave the main group'
            );
        foreach ($u_ids as $user_id){
            $u = User::find($user_id);
            if ($u->isInGroup($user->manageable_group->id))
                $u->leaveGroup($user->manageable_group->id, $delete_data);
        }
        return $u_ids;
    }
}
