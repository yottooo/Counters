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
    //TODO
    public function rules(): array
    {
        return [
            'counterName' => 'required|string|max:255',
            'type' => 'required|in:pregnancy',
            'dueDate' => 'required|date|after:today|before_or_equal:' . now()->addDays(280)->toDateString(),
            'kids' => 'nullable|array|min:1',
            'kids.*.gender' => 'required|in:male,female,unknown',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages(): array {
        return [
            'counterName.required' => 'The counter name is required.',
            'type.in' => 'The type must be "pregnancy".',
            'dueDate.required' => 'The due date is required.',
            'dueDate.after' => 'The due date must be in the future.',
            'dueDate.before_or_equal' => 'The due date must not be more than 280 days from today.',
            'kids.*.gender.required' => 'Each kid must have a gender.',
            'kids.*.gender.in' => 'Gender must be "male", "female", or "unknown".',
            'kids.min' => 'At least one kid is required for a pregnancy.',
        ];
    }
}
