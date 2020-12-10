<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GroupEmailWhitelistRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'email' =>[
                            'string',
                            'required',
                            'max:255',
                            'email',
                            Rule::unique('group_emails_whitelist')->where(function ($query) {
                                $query->where('group_id', $this->route('group_id'));
                            })
                        ]
                    ];
                }
            case 'PUT':
            case 'PATCH':
            default:
                break;
        }
    }
}
