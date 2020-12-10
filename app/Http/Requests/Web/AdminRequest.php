<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
            case 'POST':
                {
                    return [
                        'name' => 'required|min:3|max:255',
                        'email' => 'required|unique:admins,email|email',
                        'password' => 'required|min:6'
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'name' => 'required|min:3|max:255',
                        'email' => [
                            'required',
                            'unique:admins,email,'. $this->route('admin'),
                            'email'
                            ],
                        'password' => 'nullable|min:6',
                        'status' => 'boolean'
                    ];
                }
            default:
                break;
        }
    }
}
