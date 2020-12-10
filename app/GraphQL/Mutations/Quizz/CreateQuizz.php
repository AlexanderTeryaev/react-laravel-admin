<?php

namespace App\GraphQL\Mutations\Quizz;

use App\Quizz;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateQuizz
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return Quizz
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $q = $args['input'];
        $user = Request::get('user');

        $quizz = new Quizz($q);
        $quizz->group_id = $user->current_group_portal; //Todo: Get group by request
        $quizz->save();

        if (isset($q['categories']))
            $quizz->categories()->attach($q['categories']);

        if (isset($q['image']))
            $quizz->updateImage($q['image'], $user);

        if (isset($q['default_questions_image']))
            $quizz->updateDefaultImage($q['default_questions_image'], $user);

        return $quizz;
    }
}
