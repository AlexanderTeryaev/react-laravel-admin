<?php

namespace App\GraphQL\Directives\Validations;

use App\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class NotifyUsersValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'NotifyUsersValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'user_ids.*' => [
                'exists:users,id',
                function ($attribute, $value, $fail) use($user) {
                    $u = User::find($value);
                    if (!$u->isInGroup($user->current_group_portal))
                        $fail("User #{$u->id} not found");
                }
            ],
            'population_id' => [
                Rule::exists('group_populations', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                }),
            ],
            'subject' => 'max:20',
            'body' => 'max:4000'
        ];
    }
}
