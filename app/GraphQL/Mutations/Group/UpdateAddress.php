<?php

namespace App\GraphQL\Mutations\Group;

use App\Exceptions\ResolverException;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateAddress
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     *
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = Request::get('user');
        $group = $user->manageable_group;

        $stripe_user = null;

        $name = (isset($args['name'])) ? $args['name'] : $group->name;
        unset($args['name']);

        if ($group->stripe_id)
            $stripe_user = $group->updateStripeCustomer([
                'email' => $user->email,
                'name' => $name,
                'metadata' => [
                    'user_full_name' => $user->first_name .' '. $user->last_name,
                    'user_id' => $user->id,
                    'group_id' => $group->id,
                    'group_name' => $group->name
                ],
                'preferred_locales' => ['fr', 'en'],
                'address' => $args,
            ]);
        else
            $stripe_user = $group->createAsStripeCustomer([
                'email' => $user->email,
                'name' => $name,
                'metadata' => [
                    'user_full_name' => $user->first_name .' '. $user->last_name,
                    'user_id' => $user->id,
                    'group_id' => $group->id,
                    'group_name' => $group->name
                ],
                'preferred_locales' => ['fr', 'en'],
                'address' => $args
            ]);

        $address = collect($stripe_user['address'])->put('name', $stripe_user['name']);

        return $address;
    }
}
