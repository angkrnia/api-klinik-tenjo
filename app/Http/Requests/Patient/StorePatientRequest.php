<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'   => ['nullable', 'integer', 'exists:users,id'],
            'record_no' => ['nullable', 'string', 'max:50', 'unique:patients,record_no'],
            'fullname'  => ['required', 'string', 'max:255'],
            'nama_keluarga'  => ['required', 'string', 'max:255'],
            'gender'    => ['required', 'string', 'max:20'],
            'birthday'  => ['nullable', 'date'],
            'age'       => ['required', 'integer'],
            'no_ktp'    => ['nullable', 'string', 'max:16'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'address'   => ['required', 'string', 'max:255'],
            'has_allergy'  => ['nullable', 'boolean'],
            'allergy'  => ['nullable', 'string', 'max:255'],
        ];
    }
}
