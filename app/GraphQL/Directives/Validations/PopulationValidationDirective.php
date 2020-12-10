<?php

namespace App\GraphQL\Directives\Validations;

use App\GroupPopulation;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class PopulationValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'PopulationValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'population_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "updatePopulation" || $this->resolveInfo->fieldName == "deletePopulation"),
                Rule::exists('group_populations', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                }),
                function ($attribute, $value, $fail) {
                    if ($this->resolveInfo->fieldName == "deletePopulation") {
                        $population = GroupPopulation::find($value);
                        if ($population && $population->users()->count())
                            $fail("{$population->name} contains users, please move or kick them from the group before deleting the population.");
                    }
                }
            ],
            'input.name' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createPopulation"),
                'min:2',
                'max:25'
            ],
            'input.description' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createPopulation"),
                'min:2',
                'max:4000'
            ],
            'input.is_enabled' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createPopulation"),
                'boolean'
            ]
        ];
    }
}
