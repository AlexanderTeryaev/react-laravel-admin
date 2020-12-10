<?php

namespace App\GraphQL\Mutations\Group;

use App\Exceptions\ResolverException;
use App\Group;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class LeaveGroup
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @throws \App\Exceptions\ResolverException
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = Request::get('user');
        $group = Group::find($args['group_id']);
        $delete_data = (isset($args['delete_data'])) ? $args['delete_data'] : false;

        // Avoid user without group
        // Todo: default.group in global config
        if ($group->id == 1)
            throw new ResolverException(
                'Forbidden to leave the main group',
                'It is forbidden to leave the main group'
            );

        // Check if user is in group
        if (!$user->isInGroup($group->id))
            throw new ResolverException(
                'Group not found',
                'Impossible to leave a group in which you are not a subscribed'
            );

        $user->leaveGroup($group->id, $delete_data);
        return $group;
    }
}
