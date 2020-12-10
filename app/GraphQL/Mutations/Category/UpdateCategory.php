<?php

namespace App\GraphQL\Mutations\Category;

use App\Category;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateCategory
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return \App\Category
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = Request::get('user');
        $c = $args['input'];
        $category = Category::find($args['category_id']);
        $sync = $args['sync_quizz_publishing'] ?? true;

        $category->update($c);

        if (isset($c['logo']))
            $category->updateLogo($c['logo'], $user);

        if ($sync && isset($c['is_published']))
            $category->quizzes()->update(['is_published' => $c['is_published']]);

        return $category;
    }
}
