<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ISBNArray implements ValidationRule
{
    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach((array)$value as $isbn) {
            if (strlen($isbn) !== 10 && strlen($isbn) !== 13) {
                $fail('The :attribute must be numeric and 10 or 13 digits');
            }
        }
    }
}
