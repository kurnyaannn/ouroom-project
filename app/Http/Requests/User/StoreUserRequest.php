<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'full_name'     => 'required|string|max:255',
            'email'         => 'required|email|unique:tbl_user',
            'kelas'       => 'nullable|string',
            'username'      => 'required|min:5|unique:tbl_user',
            'address'       => 'nullable|string',
            'profile_picture' => 'nullable|string',
            'password'      => 'required|confirmed|min:6',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages(){
        return [
            'username.required' => 'Username tidak boleh dikosongkan',
            'username.min' => 'Username setidaknya 5 karakter',
            'username.unique' => 'Username telah digunakan',

            'email.required' => 'Username tidak boleh dikosongkan',
            'email.email' => 'Format email tidak disetujui',
            'email.unique' => 'Email telah digunakan',

            'full_name.string' => 'Gunakan huruf untuk nama lengkap anda',
            'full_name.min' => 'Gunakan setidaknya 2 karakter',

            'address.string' => 'Gunakan huruf untuk nama lengkap anda',

            'password.required' => 'Password tidak boleh dikosongkan',
            'password.confirmed' => 'Password tidak sesuai',
            'password.min' => 'Password minimal terdiri dari 6 Karakter',
        ];
    }
}
