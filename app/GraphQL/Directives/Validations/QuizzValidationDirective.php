<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class QuizzValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'QuizzValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'quizz_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "updateQuizz" || $this->resolveInfo->fieldName == "deleteQuizz"),
                Rule::exists('quizzes', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'input.name' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuizz"),
                'min:2',
                'max:50'
            ],
            'input.description' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuizz"),
                'string',
                'max:2000'
            ],
            'input.author_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuizz"),
                'numeric',
                Rule::exists('authors', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                }),
            ],
            'input.image' => 'nullable|image|mimes:jpeg,png,jpg|max:3000',
            'input.default_question_image' => 'nullable|image|mimes:jpeg,png,jpg|max:1000',
            'input.categories' => 'nullable|array',
            'input.categories.*' => Rule::exists('categories', 'id')->where(function ($query) use($user) {
                $query->where('group_id', $user->current_group_portal);
            }),
            'input.tags' => 'nullable|array',
            'input.tags.*' => 'nullable|min:2|max:20|alpha_num',
            'input.difficulty' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuizz"),
                Rule::in(['EASY', 'MEDIUM', 'HARD']),
            ],
            'input.enduro_limit' => 'min:2|max:500',
            'input.is_geolocalized' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuizz"),
                'boolean'
            ],
            'input.latitude' => [
                Rule::requiredIf((isset($this->args['input']['is_geolocalized'])) ? $this->args['input']['is_geolocalized'] : false),
                'nullable',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
            ],
            'input.longitude' => [
                Rule::requiredIf((isset($this->args['input']['is_geolocalized'])) ? $this->args['input']['is_geolocalized'] : false),
                'nullable',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'
            ],
            'input.radius' => [
                Rule::requiredIf((isset($this->args['input']['is_geolocalized'])) ? $this->args['input']['is_geolocalized'] : false),
                'nullable',
                'numeric',
                'not_in:0'
            ],
            'input.is_published' => 'boolean'
        ];
    }
}
