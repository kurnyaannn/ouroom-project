<?php

namespace App\Http\Requests\StudentClass;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTugasRequest extends FormRequest
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

    public function rules()
    {
        return [
            'nilai'      => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'nilai.integer' => 'Input nilai salah',     
        ];
    }
}
