<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method())
        {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'difficulty' => 'in:0,1,2',
                        'question' => 'bail|required|min:5|max:80',
                        'good_answer' => 'required|min:1|max:35',
                        'bad_answer' => 'required|min:1|max:35',
                        'quizz_id' => 'required|exists:quizzes,id',
                        'author_id' => 'required|exists:authors,id',
                        'more' => 'string|nullable',
                        // 'bg_url' => [
                        //     'image',
                        //     'mimes:jpeg,png,jpg,gif',
                        //     'max:1000'
                        // ],
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'difficulty' => 'in:0,1,2',
                        'question' => 'bail|required|min:5|max:80',
                        'good_answer' => 'required|min:1|max:35',
                        'bad_answer' => 'required|min:1|max:35',
                        'quizz_id' => 'required|exists:quizzes,id',
                        'author_id' => 'required|exists:authors,id',
                        'more' => 'string|nullable',
                        // 'bg_url' => [
                        //     'image',
                        //     'mimes:jpeg,png,jpg,gif',
                        //     'max:1000'
                        // ],
                    ];
                }
            default:
                break;
        }
    }
}
