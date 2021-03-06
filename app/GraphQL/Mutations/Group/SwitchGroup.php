<?php

namespace App\GraphQL\Mutations\Group;

use App\Exceptions\ResolverException;
use App\Group;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SwitchGroup
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
        $group = Group::find($args['group_id']);

        if ($user->isInGroup($group->id))
        {
            if (!$group->isOpen())
                throw new ResolverException(
                    'Group not available',
                    'Please try to switch to another group'
                );
            if ($user->switchGroup($group->id))
                return $group;
        }
        return null;
    }
}
