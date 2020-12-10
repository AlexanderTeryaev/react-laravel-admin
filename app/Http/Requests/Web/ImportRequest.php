<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportRequest extends FormRequest
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
                        'csv' => 'required|mimes:csv,txt',
                        'bg_url' => [
                            'image',
                            'mimes:jpeg,png,jpg,gif',
                            'max:1000',
                            Rule::dimensions()->ratio(16 / 10)
                        ],
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'csv' => 'required|mimes:csv,txt',
                        'bg_url' => [
                            'image',
                            'mimes:jpeg,png,jpg,gif',
                            'max:1000',
                            Rule::dimensions()->ratio(16 / 10)
                        ],
                    ];
                }
            default:
                break;
        }
    }
}
