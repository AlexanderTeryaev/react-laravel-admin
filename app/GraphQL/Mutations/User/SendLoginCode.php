<?php

namespace App\GraphQL\Mutations\User;

use App\EmailAuthentication;
use App\Exceptions\ResolverException;
use App\Notifications\Account\LoginCode;
use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SendLoginCode
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
        $email = $args['email'];
        $user = User::where('email', $email);


        # We should not inform user thank account doesn't exist (because of les méchants)
        if (!$user->exists())
        throw new ResolverException(
            __('Account not found'),
            __('No account exists for this email address, please create one.')
        );

        $old_emails = EmailAuthentication::whereNull('verified_at')->where('email', $email)->where('type', 'login');
        if ($old_emails->where('created_at', '>', now()->subMinutes(1)->toDateTimeString())->exists())
            throw new ResolverException(
                __('Too many requests'),
                __('You can request up to 1 verification emails per minute')
            );
        if ($old_emails->where('created_at', '>', now()->subHours(24)->toDateTimeString())->count() > 15)
        {
            Log::info("Spam detection: The {$email} account has been blocked because too many email login requests have been made.");
            throw new ResolverException(
                __('Your account is blocked'),
                __('You have made too many requests, please be patient before making an additional request.')
            );
        }

        $code = mt_rand(10000, 99999);

        EmailAuthentication::create([
            'type' => 'login',
            'email' => $email,
            'code' => $code,
            'ip' => Request::ip(),
            'metadata' => [
                'device-id' => Request::header('x-mrmld-device-id'),
                'os' => Request::header('x-mrmld-client-os'),
                'lang' => Request::header('x-mrmld-app-lang'),
                'app-version' => Request::header('x-mrmld-app-version'),
                'user-agent' => Request::header('User-Agent')
            ]
        ]);

        # Todo: Use user local (passed as header)
        Notification::route('mail', $email)->notify(new LoginCode($code));
        return true;
    }
}
