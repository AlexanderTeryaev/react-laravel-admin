<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
                        'name' => [
                            'bail',
                            'required',
                            'min:2',
                            'max:20',
                            Rule::unique('categories')->where(function ($query) {
                                $query->where('group_id', Auth::user()->current_group);
                            })
                        ],
                        'logo_url' => [
                            'image',
                            'mimes:jpeg,png,jpg,gif',
                            'max:1000',
                            Rule::dimensions()->ratio(1 / 1)
                        ],
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'name' => [
                            'bail',
                            'required',
                            'min:2',
                            'max:20',
                            Rule::unique('categories')->where(function ($query) {
                                $query->where('group_id', Auth::user()->current_group)->where('id', '!=', $this->route('category'));
                            })
                        ],
                        'logo_url' => [
                            'image',
                            'mimes:jpeg,png,jpg,gif',
                            'max:1000',
                            Rule::dimensions()->ratio(1 / 1)
                        ],
                    ];
                }
            default:
                break;
        }
    }
}
