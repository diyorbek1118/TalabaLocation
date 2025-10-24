<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Route'dan student model yoki id ni olish
        $student = $this->route('student'); 

        // Agar model bo‘lsa id sini olish
        $id = $student instanceof \App\Models\User ? $student->id : $student;

        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'phone' => 'sometimes|string|max:20',
            'password' => 'sometimes|string|min:6|confirmed',

            'student_profile.faculty' => 'sometimes|string|max:255',
            'student_profile.department' => 'sometimes|string|max:255',
            'student_profile.semester' => 'sometimes|integer|min:1',
            'student_profile.group_name' => 'sometimes|string|max:50',
            'student_profile.course' => 'sometimes|string|max:50',
            'student_profile.tutor' => 'sometimes|string|max:255',
            'student_profile.rent_area' => 'sometimes|string|max:255',
            'student_profile.rent_address' => 'sometimes|string|max:500',
            'student_profile.rent_map_url' => 'sometimes|url',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
