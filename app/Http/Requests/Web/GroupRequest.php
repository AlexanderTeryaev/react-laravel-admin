<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
                    'name' => 'bail|required|unique:groups|min:2|max:30',
                    // 'logo_url' => 'image|mimes:jpeg,png,jpg,gif|max:1000',
                    'users_limit' => 'required|integer',
                    'trial_ends_at' => 'required|date'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'bail|required|min:3|max:30|unique:groups,name,'. $this->route('group'),
                    // 'logo_url' => 'image|mimes:jpeg,png,jpg,gif|max:1000',
                    'users_limit' => 'required|integer',
                    'trial_ends_at' => 'required|date',
                    'status' => 'boolean'
                ];
            }
            default:
                break;
        }
    }
}
