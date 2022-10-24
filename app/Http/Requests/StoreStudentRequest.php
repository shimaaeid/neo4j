<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
        return [
            //
            'name' => 'required',
            'subject' => 'required'
        ];
    }

    public function messages()
    {
        return[

            'name.required' => 'U must enter your name',
            'subject.required' => 'U must enter your subjects',
        ];
    }
}
