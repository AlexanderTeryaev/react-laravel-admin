<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class MovePopulationValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'MovePopulationValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'user_ids.*' => 'required_without:user_ids|exists:users,id',
            'population_src' => [
                'required_without:user_ids',
                Rule::exists('group_populations', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'population_dest' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "movePopulationUsers"),
                Rule::exists('group_populations', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ]
        ];
    }
}
