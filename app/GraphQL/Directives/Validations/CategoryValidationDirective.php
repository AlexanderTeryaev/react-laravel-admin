<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class CategoryValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'CategoryValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'category_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "updateCategory" || $this->resolveInfo->fieldName == "deleteCategory"),
                Rule::exists('categories', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'input.name' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createCategory"),
                'min:2',
                'max:20'
            ],
            'input.pic' => 'image|mimes:jpeg,png,jpg|max:3000',
            'input.is_published' => 'boolean'
        ];
    }
}
