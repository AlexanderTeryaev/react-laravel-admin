<?php

namespace App\GraphQL\Mutations\Group;

use App\Exceptions\ResolverException;
use App\GroupInvitation;
use App\Notifications\Account\GroupInvitations;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SendGroupEmailVerification
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
        $email = $args['email'];

        if (GroupInvitation::whereNull('accepted_at')->where('email', $email)->where('updated_at', '>', now()->subMinutes(1)->toDateTimeString())->exists())
            throw new ResolverException(
                __('Too many requests'),
                __('You can request up to 1 verification emails per minute')
            );

        $invitations = collect([
                $user->getPendingInvitations($email),
                $user->createInvitationByDomainMatching($email),
                $user->createInvitationGroupManagers($email)
        ])->collapse();


        if (!$invitations->count())
            throw new ResolverException(
                __('No match'),
                __('The email address provided does not correspond to any private group')
            );

        try {
            $user->email = $email;
            Mail::to($user)->send(new \App\Mail\GroupInvitations($invitations));
        } catch (\Exception $exception) {
            Log::error('Error when trying to send user email verification: ' . $exception);
            throw new ResolverException(
                __('Internal server error'),
                __('Please be patient, we have been notified of the issue, we are doing our best to resolve the issue')
            );
        }

        return true;
    }
}
