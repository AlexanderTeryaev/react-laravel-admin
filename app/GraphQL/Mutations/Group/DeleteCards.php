<?php

namespace App\GraphQL\Mutations\Group;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class DeleteCards
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
        $user = Request::get('user');
        $group = $user->manageable_group;

        if ($group->hasPaymentMethod())
        {
            $group->deletePaymentMethods();
            return true;
        }
        return false;
    }
}
