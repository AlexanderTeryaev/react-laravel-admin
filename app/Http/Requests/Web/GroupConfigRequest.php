<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GroupConfigRequest extends FormRequest
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
                        'key' => [
                            'bail',
                            'string',
                            'required',
                            'min:2',
                            'max:20',
                            Rule::unique('group_configs')->where(function ($query) {
                                $query->where('group_id', $this->route('group_id'));
                            })

                        ],
                        'value' => 'required|min:1',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'key' => [
                            'bail',
                            'string',
                            'required',
                            'min:2',
                            'max:20',
                            Rule::unique('group_configs')->where(function ($query) {
                                $query->where('group_id', $this->route('group_id'))->where('id', '!=', $this->route('id'));
                            })

                        ],
                        'value' => 'required|min:1',
                    ];
                }
            default:
                break;
        }
    }
}
