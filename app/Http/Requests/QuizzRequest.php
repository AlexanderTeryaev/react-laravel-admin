<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class QuizzRequest extends FormRequest
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
        $group_id = Auth::user()->current_group;

        return [
            'name' => 'bail|required|min:2|max:50',
            'description' => 'required|string|max:2000',
            'author_id' => [
                    'required',
                    'numeric',
                    Rule::exists('authors', 'id')->where(function ($query) use($group_id) {
                        $query->where('group_id', $group_id);
                    }),
            ],
            'bg_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1000',
            'default_img_url' => 'nullable|image|dimensions:ratio=16/10|mimes:jpeg,png,jpg,gif|max:1000',
            'categories' => 'nullable|array',
            'categories.*' => Rule::exists('categories', 'id')->where(function ($query) use($group_id) {
                $query->where('group_id', $group_id);
            }),
            'enduroLimit' => 'required|numeric',
            'latitude' => [
                'nullable',
                'regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'
            ],
            'longitude' => [
                'nullable',
                'regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/',
            ],
            'radius' => [
                'nullable',
                'regex:/^[1-9][0-9]+/',
                'not_in:0',
            ]
        ];
    }
}
