<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class AllowedDomainValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'AllowedDomainValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'domain_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "deleteAllowedDomain"),
                Rule::exists('group_allowed_domains', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'population_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createAllowedDomain"),
                Rule::exists('group_populations', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'domain' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createAllowedDomain"),
                Rule::unique('group_allowed_domains', 'domain')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ]
        ];
    }
}
