<?php

namespace App\GraphQL\Mutations\Quizz;

use App\Quizz;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateQuizz
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return \App\Quizz
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = Request::get('user');
        $q = $args['input'];
        $quizz = Quizz::find($args['quizz_id']);

        $quizz->update($q);

        if (isset($q['categories']))
            $quizz->categories()->sync($q['categories']);

        if (isset($q['image']))
            $quizz->updateImage($q['image'], $user);

        if (isset($q['default_questions_image']))
        {
            $quizz->updateDefaultImage($q['default_questions_image'], $user);
            $quizz->questions()->update(['bg_url' => $quizz->default_questions_image]);
        }
        
        return $quizz;
    }
}
