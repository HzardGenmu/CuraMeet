<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string|in:patient,doctor,admin',
        ];

        // Doctor-specific validation
        if ($this->input('role') === 'doctor') {
            $rules['str_number'] = 'required|string|unique:doctors,str_number';
            $rules['full_name'] = 'required|string|max:255';
            $rules['specialist'] = 'required|string|max:255';
            $rules['polyclinic'] = 'required|string|max:255';
            $rules['available_time'] = 'nullable|string|max:255';
        }

        // Patient-specific validation
        if ($this->input('role') === 'patient') {
            $rules['NIK'] = 'nullable|string|max:20|unique:patients,NIK';
            $rules['full_name'] = 'nullable|string|max:255';
            $rules['picture'] = 'nullable|string|max:255';
            $rules['allergies'] = 'nullable|string';
            $rules['disease_histories'] = 'nullable|string';
        }

        return $rules;
    }
}
