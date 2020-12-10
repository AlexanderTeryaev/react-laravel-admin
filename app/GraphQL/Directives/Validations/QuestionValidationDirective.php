<?php

namespace App\GraphQL\Directives\Validations;

use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class QuestionValidationDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'QuestionValidation';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        $user = Request::get('user');
        return [
            'question_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "updateQuestion" || $this->resolveInfo->fieldName == "deleteQuestion"),
                Rule::exists('questions', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'input.quizz_id' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuestion"),
                Rule::exists('quizzes', 'id')->where(function ($query) use($user) {
                    $query->where('group_id', $user->current_group_portal);
                })
            ],
            'input.question' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuestion"),
                'min:5',
                'max:80',
            ],
            'input.good_answer' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuestion"),
                'min:1',
                'max:35'
            ],
            'input.bad_answer' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuestion"),
                'min:1',
                'max:35'
            ],
            'input.difficulty' => [
                Rule::requiredIf($this->resolveInfo->fieldName == "createQuestion"),
                Rule::in(['EASY', 'MEDIUM', 'HARD']),
            ],
            'input.more' => 'nullable|string',
            'input.bg' => 'image|mimes:jpeg,png,jpg|max:3000',
        ];
    }
}
