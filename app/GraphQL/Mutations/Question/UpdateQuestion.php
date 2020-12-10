<?php

namespace App\GraphQL\Mutations\Question;

use App\Question;
use App\Quizz;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateQuestion
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
        $q = $args['input'];
        $user = Request::get('user');
        $question = Question::find($args['question_id']);
        $quizz_id = $q['quizz_id'] ?? $question->quizz_id;
        $quizz = Quizz::find($quizz_id);

        $question->update($q);

        if ($args['useDefaultImage'] && $quizz->default_questions_image)
            $question->update(['bg_url' => $quizz->default_questions_image]);
        else if (isset($q['bg']))
            $question->updateImage($q['bg'], $user);

        return $question;
    }
}
