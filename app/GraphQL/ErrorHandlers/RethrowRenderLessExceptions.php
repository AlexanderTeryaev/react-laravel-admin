<?php
namespace App\GraphQL\ErrorHandlers;

use App\User;
use Closure;
use GraphQL\Error\Error;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Nuwave\Lighthouse\Execution\ErrorHandler;


class RethrowRenderLessExceptions implements ErrorHandler
{
    /**
     * Handle Exceptions that implement Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions
     * and add extra content from them to the 'extensions' key of the Error that is rendered
     * to the User.
     *
     * @param  \GraphQL\Error\Error  $error
     * @param  \Closure  $next
     * @return array
     */
    public static function handle(Error $error, Closure $next): array
    {
        $underlyingException = $error->getPrevious();
        // Only report the exception if it is renderless.
        // Most exceptions that render, are user-errors, that should not be logged (eg. invalid input, etc.).
        if (!$underlyingException instanceof RendersErrorsExtensions) {
            $user = Request::get('user');
            if (!$user && Request::header('x-mrmld-device-id'))
                $user = User::where('device_id', '=', Request::header('x-mrmld-device-id'))->first();
            $context = ($user) ? collect($user->debugInfos()) : collect();
            $context->put('Query', $error->getSource()->body);
            $context->put('Variables', Request::get('variables'));
            Log::error($underlyingException, $context->toArray());
        }
        return $next($error);
    }
}