<?php

namespace App\GraphQL\Mutations\Author;

use App\Author;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateAuthor
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return \App\Author
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) : Author
    {
        $user = Request::get('user');
        $a = $args['input'];
        $author = Author::find($args['author_id']);

        $author->update($a);

        if (isset($a['pic']))
            $author->updatePicture($a['pic'], $user);

        return $author;
    }
}
