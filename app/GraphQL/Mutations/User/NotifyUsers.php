<?php

namespace App\GraphQL\Mutations\User;

use App\GroupPopulation;
use App\Notifications\Push\Informative;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class NotifyUsers
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

        if (isset($args['user_ids']))
            $users = User::whereIn('id', $args['user_ids'])->get();
        elseif (isset($args['population_id']))
            $users = GroupPopulation::find($args['population_id'])->users()->get();
        else
            $users = $group->users()->get();

        Notification::send($users, new Informative($user->manageable_group, $args['body']));
        return true;
    }
}
