<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeUser = $this->route('admin') 
            ?? $this->route('renter') 
            ?? null;

        $userId = $routeUser instanceof \App\Models\User ? $routeUser->id : $routeUser;

        return [
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes','email',Rule::unique('users', 'email')->ignore($userId)],
            'phone' => 'sometimes|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
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
