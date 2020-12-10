<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class FeaturedValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'FeaturedValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'featured_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "updateFeatured" || $this->resolveInfo->fieldName == "deleteFeatured"),
                Rule::exists('featured', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'input.name' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createFeatured"),
                'min:2',
                'max:20'
            ],
            'input.description' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createFeatured"),
                'string',
                'max:4000'
            ],
            'input.order_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createFeatured"),
                'numeric'
            ],
            'input.is_published' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createFeatured"),
                'boolean'
            ],
            'input.pic' => 'image|mimes:jpeg,png,jpg|max:3000',
            'input.quizzes' => 'nullable|array',
            'input.quizzes.*' => Rule::exists('quizzes', 'id')->where(function ($query) use($user) {
                $query->where('group_id',  $user->current_group_portal);
            })
        ];
    }
}
