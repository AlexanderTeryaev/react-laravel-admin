<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class KickUsersValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'KickUsersValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'user_ids.*' => 'exists:users,id',
            'delete_data' => 'boolean'
        ];
    }
}
