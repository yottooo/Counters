<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePregnancyCounterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'counterName' => 'required|string|max:255',
            'type' => 'required|in:pregnancy',
            'gender' => 'required|in:male,female',
            'birthday' => 'required|date',
            ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'counterName.required' => 'The counter name field is required.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The type must be "kid".',
            'gender.required_if' => 'The gender field is required when the type is "kid".',
            'gender.in' => 'The gender must be either male or female.',
            'birthday.required_if' => 'The birthday field is required when the type is "kid".',
            'birthday.date' => 'The birthday must be a valid date.',
        ];
    }
}
