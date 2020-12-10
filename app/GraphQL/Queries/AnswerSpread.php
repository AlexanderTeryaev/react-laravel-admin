<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AnswerSpread
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
        $results = ['right', 'wrong'];
        $counts = collect();

        foreach ($results as $result){
            $total = DB::table('users')
                ->join('user_answers', 'users.id', '=', 'user_answers.user_id')
                ->where('user_answers.result', '=', ($result == "right") ? true : false)
                ->where('user_answers.group_id', $user->manageable_group->id);

            if (isset($args['start']))
                $total->where('user_answers.answered_at', '>', $args['start']);
            if (isset($args['end']))
                $total->where('user_answers.answered_at', '<', $args['end']);

            $counts->push($total->count());
        }
        return [
            'data' => $counts,
            'labels' => $results
        ];
    }
}
