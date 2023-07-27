<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Mobile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(! preg_match('/(09)(0[1-5]|1[0-9]|2[0-2]|3[0-9]|4[1]|9[0-2])([0-9]{7})/',$value)) {
            $fail('the mobile format is invalid.');
        }
    }
}
