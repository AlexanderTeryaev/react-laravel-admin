<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TrainingRequest extends FormRequest
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
            case 'PATCH':
            case 'PUT':
            case 'POST':
            {
                return [
                    'name' => 'bail|required|min:2|max:50',
                    'description' => 'required|string',
                    'price' => 'required|integer',
                    'subtitle' => 'required|string',
                    'difficulty' => 'in:0,1,2',
                    'author_id' => 'required|numeric|exists:shop_authors,id',
                    'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1000',
                ];
            }
            default:
                break;
        }
    }
}
