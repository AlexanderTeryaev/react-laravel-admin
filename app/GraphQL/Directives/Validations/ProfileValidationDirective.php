<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class ProfileValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'ProfileValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'first_name' => 'min:2|max:30',
            'last_name' => 'min:2|max:30',
            'username' => [
                'min:4',
                'max:15',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id)
            ],
            'bio' => 'nullable|string|max:50',
            'one_signal_id' => 'nullable|size:36|string|alpha_dash',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:3000',
            'is_onboarded' => 'boolean',
            // Remove this line bellow when deleting deprecated profile mutations (updateBio, updateAvatar...)
            'player_id' => 'nullable|size:36|string|alpha_dash',
        ];
    }
}
