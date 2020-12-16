<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Http\Request;

class ProfileRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'email'           => 'required|email|unique:tbl_user,email,'. $request->get('iduser'),
            'full_name'       => 'required|string|min:2',
            'address'         => 'string|nullable',
            'file'            => 'nullable|max:2000',
            'profile_picture' => 'string|nullable',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'full_name.required' => 'Nama tidak boleh dikosongkan',
            'full_name.string' => 'Gunakan huruf untuk nama lengkap anda',
            'full_name.min' => 'Gunakan setidaknya 2 karakter',

            'email.required' => 'Email tidak boleh dikosongkan',
            'email.email' => 'Format email tidak disetujui',
            'email.unique' => 'Email telah digunakan sebelumnya',

            'file.max' => 'Maksimum file 100 KB',

            'address.string' => 'Gunakan huruf untuk nama lengkap anda',
        ];
    }
}
