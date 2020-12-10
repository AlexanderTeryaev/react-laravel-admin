<?php

namespace App\GraphQL\Queries;

use App\Category;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class DigDeeper
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
        $quizz_ids = collect();

        $answers = $user->answers()
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('quizzes', 'questions.quizz_id', '=', 'quizzes.id')
            ->select('user_answers.*')
            ->whereNotNull('questions.more')
            ->where('questions.more', '!=', '')
            ->where('quizzes.is_published', true)
            ->orderBy('user_answers.answered_at', 'DESC');

        if (isset($args['category_ids']))
        {
            foreach ($args['category_ids'] as $category_id){
                $category = Category::find($category_id);
                $quizz_ids = $quizz_ids->concat($category->quizzes->pluck('id'));
            }
            if ($quizz_ids->count())
                $answers->whereIn('questions.quizz_id', $quizz_ids->unique()->values());
        }
        return $answers;
    }
}
