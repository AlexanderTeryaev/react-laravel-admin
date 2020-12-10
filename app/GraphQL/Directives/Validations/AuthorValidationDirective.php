<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class AuthorValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'AuthorValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'author_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "updateAuthor" || $this->resolveInfo->fieldName == "deleteAuthor"),
                Rule::exists('authors', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'input.name' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createAuthor"),
                'min:2',
                'max:25'
            ],
            'input.function' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createAuthor"),
                'min:2',
                'max:25'
            ],
            'input.description' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createAuthor"),
                'min:2',
                'max:2000'
            ],
            'input.pic' => 'image|mimes:jpeg,png,jpg|max:3000',
            'input.fb_link' => 'nullable|url',
            'input.twitter_link' => 'nullable|url',
            'input.website_link' => 'nullable|url',
        ];
    }
}
