<?php

namespace App\GraphQL\Queries;

use App\Quizz;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class QuizzesSearchable
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
        // Todo: Create current_group var in request param
        if (Auth::check())
            $group_id = $user->current_group_portal;
        else
            $group_id =  $user->current_group;

        if (isset($args['search']))
            return Quizz::search($args['search'])->where('group_id', $group_id)->where('is_published', true);
        return Quizz::published();
    }
}
