<?php

namespace App\GraphQL\Mutations\Quizz;

use App\Exceptions\ResolverException;
use App\Notifications\Push\NewQuizz;
use App\Quizz;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class NewQuizzNotification
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
        $users = $user->manageable_group->users()->get();
        $quizz = Quizz::find($args['quizz_id']);

        if (!$quizz)
            throw new ResolverException(
                'Quizz not found',
                'The quizz_id does not belong to any quizz'
            );

        Notification::send($users, new NewQuizz($quizz));
        return true;
    }
}
