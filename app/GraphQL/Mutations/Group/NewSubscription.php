<?php

namespace App\GraphQL\Mutations\Group;

use App\Exceptions\ResolverException;
use App\Plan;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class NewSubscription
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
        $group = $user->manageable_group;
        $plan = Plan::find($args['plan_id']);
        $payment_method = $args['payment_method'];

        if ($group->subscribed('main'))
            throw new ResolverException(
                'You are already subscribed to a plan',
                'You can not combine multiple plans'
            );

        try {
            $subscription =  $group->newSubscription('main', $plan->plan_id)->create($payment_method, [
                'email' => $user->email,
                'metadata' => [
                    'user_id' => $user->id,
                    'group_id' => $group->id,
                    'group_name' => $group->name
                ]
            ]);
        } catch (\Exception $e){
            Log::error("Error when user $user->email subscribing to plan $plan->plan_id for group $group->name. [" . $e->getMessage() . "]");
            throw new ResolverException(
                $e->getMessage(),
                'Please check provided params'
            );
        }
        $group->update([
            'trial_ends_at' => now(),
            'users_limit' => $plan->users_limit
        ]);
        $group->increment('coins');
        return $subscription;
    }
}