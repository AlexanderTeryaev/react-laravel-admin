<?php

namespace App\GraphQL\Mutations\Group;

use App\CoinsPack;
use App\Exceptions\ResolverException;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class BuyCoinsPack
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
        $coin_pack = CoinsPack::find($args['coins_pack_id']);

        try {
            $group->invoiceFor($coin_pack->name, $coin_pack->price * 100);
        } catch (\Exception $e){
            Log::error("Error when user $user->email buying coins pack $coin_pack->name for group $group->name.  [" . $e->getMessage() . "]");
            throw new ResolverException(
                $e->getMessage(),
                'Please check provided params'
            );
        }
        $group->increment('coins', $coin_pack->coins_quantity);
        return true;
    }
}
