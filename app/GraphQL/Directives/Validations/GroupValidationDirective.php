<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class GroupValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'GroupValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        return [
            'name' => 'min:2|max:20',
            'description' => 'nullable|min:2|max:2000',
            'pic' => 'image|mimes:jpeg,png,jpg|max:3000'
        ];
    }
}
