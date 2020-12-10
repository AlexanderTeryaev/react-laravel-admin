<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class SubscribeUsersValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'SubscribeUsersValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'user_ids.*' => 'exists:users,id',
            'quizz_ids.*' => Rule::exists('quizzes', 'id')->where(function ($query) use($user) {
                $query->where('group_id', $user->current_group_portal);
            })
        ];
    }
}
