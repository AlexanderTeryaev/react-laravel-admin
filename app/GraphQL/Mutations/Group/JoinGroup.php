<?php

namespace App\GraphQL\Mutations\Group;

use App\Exceptions\ResolverException;
use App\Group;
use App\GroupInvitation;
use App\GroupPopulation;
use App\UserEmailValidation;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class JoinGroup
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
        $code = $args['code'];
        $switch = (isset($args['switch'])) ? $args['switch'] : false;

        if (strlen($code) == 36) {
            $invitation = GroupInvitation::where('token', $code)->whereNull('accepted_at')->first();
            if ($invitation) {
                $group = Group::find($invitation->group_id);
                if ($group && $group->isOpen() && !$group->isFull()) {
                    if (!$user->isInGroup($group->id))
                        $user->addInGroup(
                            $invitation->group->id,
                            'email',
                            $invitation->email,
                            $invitation->population->id
                        );
                    if ($switch)
                        $user->switchGroup($group->id);
                    $invitation->update(['accepted_at' => now()]);
                    return [$group];
                } else {
                    throw new ResolverException(
                        __('The group is no longer available'),
                        __('This group is not available at the moment, please try again later.')
                    );
                }
            } else {
                throw new ResolverException(
                    __('Invalid invitation'),
                    __('The invitation is no longer valid, please check with the sender.')
                );
            }
        }

        $pop_by_mk = GroupPopulation::where('master_key', '=', $code)->first();
        if ($pop_by_mk) {
            if (!$pop_by_mk->group->isOpen() || $pop_by_mk->group->isFull())
                throw new ResolverException(
                    __('The group is no longer available'),
                    __('This group is not available at the moment, please try again later.')
                );
            if (!$user->isInGroup($pop_by_mk->group->id))
                $user->addInGroup($pop_by_mk->group->id, 'master_key', null, $pop_by_mk->id);
            if ($switch)
                $user->switchGroup($pop_by_mk->group->id);
            return [$pop_by_mk->group];
        }

        throw new ResolverException(
            __('Incorrect access code'),
            __('The code you entered does not correspond to any record, check the code and enter it again.')
        );
    }
}
