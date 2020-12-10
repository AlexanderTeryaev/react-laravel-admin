<?php

namespace App\Http\Requests\Web;

use App\Rules\Domain;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class GroupEmailDomainRequest extends FormRequest
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
                        'domain' => [
                            'domain',
                            'string',
                            'required',
                            'max:255',
                            Rule::unique('group_email_domains')->where(function ($query) {
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
