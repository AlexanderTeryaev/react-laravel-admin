<?php

namespace App\GraphQL\Mutations\Quizz;

use App\Exceptions\ResolverException;
use App\Quizz;
use App\UserSubscription;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SubscribeQuizz
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @throws ResolverException
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = Request::get('user');
        $quizz = Quizz::find($args['quizz_id']);

        if (!isset($quizz))
            return null;

        if (!$quizz->is_published)
            throw new ResolverException(
                'Quizz not available',
                'You cannot subscribe to this quiz because it is not activated.'
            );

        if (!$user->isSubscribed($quizz->id))
            return $user->subscribe($quizz);

        return null;
    }
}
