<?php

namespace App\GraphQL\Mutations\Group;

use App\Author;
use App\Group;
use App\Helpers\AppHelper;
use App\Mail\UserEmailVerification;
use App\Notifications\Account\EmailVerification;
use App\Notifications\Slack\NewGroupCreated;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use TaylorNetwork\UsernameGenerator\Facades\UsernameGenerator;

class CreateGroup
{
    /**
     * Return a value for the field.
     *
     * @param null                                                $rootValue   Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param mixed[]                                             $args        The arguments that were passed into the field.
     * @param GraphQLContext $context     Arbitrary data that is shared between all fields of a single query.
     * @param ResolveInfo $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     *
     * @return bool
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): bool
    {
        $g = $args['input'];

        // User creation
        $user = User::create([
            'current_group' => 1,
            'last_ip' => Request::ip(),
            'curr_os' => 'portal',
            'first_name' => $g['first_name'],
            'last_name' => $g['last_name'],
            'email' => $g['email'],
            'phone_number' => $g['phone_number'],
            'username' => UsernameGenerator::generate($g['first_name'] .' '. $g['last_name']),
        ]);

        $user->addInGroup(1, 'default');

        $group = Group::create([
            'name' => $g['name'],
            'description' => (isset($g['description'])) ? $g['description'] : null,
            'trial_ends_at' => now()->addDays(AppHelper::instance()->getConfig('group.trial_days', 6)),
        ]);

        $author = Author::create([
            'group_id' => $group->id,
            'name' => $g['name'],
            'description' => (isset($g['description'])) ? $g['description'] : null,
        ]);

        if (isset($g['logo']))
        {
            $group->updateImage($g['logo'], $user);
            $author->updatePicture($g['logo'], $user);
        }

        $user->manageable_groups()->sync($group->id);

        $user->password = Hash::make($g['password']);
        $user->current_group_portal = $group->id;
        $user->save();

        Notification::route('slack', env('SLACK_WEBHOOK_URL'))->notify(new NewGroupCreated($user, $group));
        $user->notify(new EmailVerification());
        return true;
    }
}
