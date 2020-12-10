<?php

namespace App\GraphQL\Mutations\Group;

use App\Exceptions\ResolverException;
use App\Plan;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateDefaultCard
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
        $payment_method = $args['payment_method'];

        try {
            $pm = $group->updateDefaultPaymentMethod($payment_method);
            return [
                'brand' => $pm->card->brand,
                'exp_month' => $pm->card->exp_month,
                'exp_year' => $pm->card->exp_year,
                'last4' => $pm->card->last4,
            ];
        } catch (\Exception $e){
            Log::error("Error when user $user->email update default card for group $group->name [" . $e->getMessage() . "]");
            throw new ResolverException(
                $e->getMessage(),
                'Please check provided params'
            );
        }
    }
}
