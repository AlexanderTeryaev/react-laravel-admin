<?php

namespace App\GraphQL\Mutations\User;

use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UploadAvatar
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
        $file = $args['avatar'];
        $ext = strtolower($file->getClientOriginalExtension());
        $image_name = 'users/avatar_'. $user->id . '_' . Carbon::now()->timestamp . '.' . $ext;

        Storage::put($image_name, file_get_contents($file), 'public');

        $user->avatar_url = $image_name;
        $user->save();

        return $user;
    }
}
