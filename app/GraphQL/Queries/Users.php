<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use App\User;

class Users
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

        if (isset($args['search']))
        {
            $search = User::search($args['search'])->where('groups_ids', $group->id);
            if (isset($args['population_ids']) && $args['population_ids'] != [])
                $search->whereIn('populations_ids', $args['population_ids']);
            if (isset($args['curr_os']))
                $search->with([
                    'filters' => 'curr_os:'. $args['curr_os']
                ]);
            if (isset($args['withEmail']) && $args['withEmail'])
                $search->where('groups_emails', $group->id);
            if (isset($args['withEmail']) && !$args['withEmail'])
                $search->where('groups_emails', '!=', $group->id);

            return $search;
        }

        $query = $user->manageable_group
            ->users()
            ->join('user_statistics', 'users.id', '=', 'user_statistics.user_id')
            ->where('user_statistics.group_id', $group->id);

        if (isset($args['population_ids']) && $args['population_ids'] != [])
            $query->whereIn('user_groups.population_id', $args['population_ids']);
        if (isset($args['curr_os']))
            $query->where('curr_os', $args['curr_os']);
        if (isset($args['withEmail']) && $args['withEmail'])
            $query->whereNotNull('user_groups.email');
        if (isset($args['withEmail']) && !$args['withEmail'])
            $query->whereNull('user_groups.email');

        return $query;
    }
}
