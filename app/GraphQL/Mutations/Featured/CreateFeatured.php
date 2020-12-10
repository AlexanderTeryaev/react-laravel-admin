<?php

namespace App\GraphQL\Mutations\Featured;

use App\Featured;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateFeatured
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
        $f = $args['input'];

        $featured = new Featured($f);
        $featured->group_id = $user->current_group_portal;
        $featured->save();

        if (isset($f['quizzes']))
            $featured->quizzes()->attach($f['quizzes']);

        if (isset($f['pic']))
            $featured->updateImage($f['pic'], $user);

        return $featured;
    }
}
