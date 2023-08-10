<?php

namespace App\Http\Requests\User;

use App\Rules\Mobile;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'mobile' => ['required', 'digits:11', new Mobile()],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'token' => ['required']
        ];
    }
}
