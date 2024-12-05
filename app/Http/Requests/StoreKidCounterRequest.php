<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKidCounterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        //Todo add validation for birthday to no more than 25 years in the past from now
        return [
            'counterName' => 'required|string|max:255',
            'type' => 'required|in:kid',
            'kidName' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birthday' => 'required|date|before:today',
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
            'kidName.required' => 'The kid name field is required.',
            'type.required' => 'The type field is required.',
            'type.in' => 'The type must be "kid".',
            'gender.required_if' => 'The gender field is required when the type is "kid".',
            'gender.in' => 'The gender must be either male or female.',
            'birthday.required_if' => 'The birthday field is required when the type is "kid".',
            'birthday.date' => 'The birthday must be a valid date.',
        ];
    }
}
