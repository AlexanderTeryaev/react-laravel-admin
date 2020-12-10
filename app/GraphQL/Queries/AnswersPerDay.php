<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AnswersPerDay
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

       $answers = DB::table('user_answers')
           ->selectRaw("DATE(answered_at) as 'labels'")
           ->selectRaw("COUNT(*) as 'total'")
           ->selectRaw("SUM(result) as 'right'")
           ->selectRaw("SUM(!result) as 'wrong'")
           ->where('group_id', $user->manageable_group->id);


       if (isset($args['start']))
           $answers->where('answered_at', '>', $args['start']);
       if (isset($args['end']))
           $answers->where('answered_at', '<', $args['end']);

       $data = $answers->groupBy(DB::raw('DATE(answered_at)'))->orderBy('labels')->get();

        return [
            'labels' => $data->pluck('labels'),
            'total' => $data->pluck('total'),
            'right' => $data->pluck('right'),
            'wrong' => $data->pluck('wrong')
        ];
    }
}
