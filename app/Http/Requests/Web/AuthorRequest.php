<?php

namespace App\Http\Requests\Web;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class AuthorRequest extends FormRequest
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
                        'name' => 'bail|required|min:4|max:50',
                        'function' => 'required|min:2|max:40',
                        'description' => 'required|string|max:2000',
                        'pic_url' => 'image|mimes:jpeg,png,jpg,gif|max:1000',
                        'fb_link' => 'nullable|url',
                        'twitter_link' => 'nullable|url',
                        'website_link' => 'nullable|url',
                        'email' => 'string|max:255|email|unique:authors',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'name' => 'bail|required|min:4|max:50',
                        'function' => 'required|min:2|max:40',
                        'description' => 'required|string|max:2000',
                        'pic_url' => 'image|mimes:jpeg,png,jpg,gif|max:1000',
                        'fb_link' => 'nullable|url',
                        'twitter_link' => 'nullable|url',
                        'website_link' => 'nullable|url',
                    ];
                }
            default:
                break;
        }
    }
}
