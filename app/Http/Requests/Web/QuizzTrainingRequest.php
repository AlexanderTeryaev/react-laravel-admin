<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuizzTrainingRequest extends FormRequest
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
        return [
            'name' => 'bail|required|min:2|max:50',
            'description' => 'required|string|max:2000',
            'training_id' => [
                'required',
                'numeric',
                Rule::exists('shop_trainings', 'id')
            ],
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1000',
        ];
    }
}
