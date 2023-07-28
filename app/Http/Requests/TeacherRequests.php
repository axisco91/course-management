<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequests extends FormRequest
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
            'name' => 'required|string',
            'surname' => 'required|string',
            'dni' => 'required|string',
            'telephone' => 'required|string',
            'email' => 'email|required',
            'user' => 'required|string',
            'password' => 'required|string'
        ];
    }
}
