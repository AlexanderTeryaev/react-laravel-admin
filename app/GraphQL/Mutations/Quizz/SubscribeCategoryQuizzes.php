<?php

namespace App\GraphQL\Mutations\Quizz;

use App\Category;
use App\Exceptions\ResolverException;
use App\Quizz;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SubscribeCategoryQuizzes
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @throws \App\Exceptions\ResolverException
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = Request::get('user');
        $category = Category::find($args['category_id']);
        $subscriptions = collect();

        if (!isset($category))
            throw new ResolverException(
                'Category not found',
                'The category_id is not found'
            );

        foreach ($category->quizzes as $quizz)
            $subscriptions->push($user->subscribe($quizz));
        return $subscriptions;
    }
}
