<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class CreateGroupValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'CreateGroupValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        return [
            'input.name' => 'min:2|max:20',
            'input.pic' => 'image|mimes:jpeg,png,jpg|max:3000',
            'input.description' => 'min:2|max:2000',
            'input.first_name' => 'min:2|max:30',
            'input.last_name' => 'min:2|max:30',
            'input.email' => [
                'email',
                Rule::unique('users', 'email')
            ],
            'input.phone_number' => 'phone:AUTO,FR',
            'input.password' => 'min:8'
        ];
    }
}
