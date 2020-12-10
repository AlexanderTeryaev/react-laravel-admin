<?php

namespace App\Http\Requests\Web;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ManagerRequest extends FormRequest
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
                        'email' => 'required|string|max:255|email',
                        'username' => 'nullable|string|exists:users',
                        'first_name' => 'nullable|max:15',
                        'last_name' => 'nullable|max:15',
                    ];
                }
            case 'PUT':
            case 'PATCH':
            default:
                break;
        }
    }
}
