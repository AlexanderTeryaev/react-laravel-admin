<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class FeaturedRequest extends FormRequest
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
                        'name' => 'bail|required|min:2|max:20',
                        'description' => 'required|string|max:4000',
                        // 'pic_url' => 'image|mimes:jpeg,png,jpg,gif|max:30000',
                        'order_id' => 'required|integer'
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'name' => 'bail|required|min:2|max:20',
                        'description' => 'required|string|max:4000',
                        // 'pic_url' => 'image|mimes:jpeg,png,jpg,gif|max:30000',
                        'order_id' => 'required|integer'
                    ];
                }
            default:
                break;
        }
    }
}
