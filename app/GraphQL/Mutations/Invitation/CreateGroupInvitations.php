<?php

namespace App\GraphQL\Mutations\Invitation;

use App\GroupInvitation;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateGroupInvitations
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
        $group = $user->manageable_group;
        $invitations = collect();

        foreach ($args['emails'] as $email) {
            $invitation = GroupInvitation::create([
                'group_id' => $group->id,
                'population_id' => $args['population_id'],
                'email' => $email,
                'token' => Str::uuid()->toString()
            ]);
            Notification::route('mail', $email)
                ->notify((new \App\Notifications\Account\GroupInvitation($invitation, $user))->locale($user->curr_app_lang));

            $invitations->push($invitation);
        }
        return $invitations;
    }
}
