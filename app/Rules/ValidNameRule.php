<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidNameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || trim($value) === '') {
            $fail('El nombre completo es obligatorio.');
            return;
        }

        if (preg_match('/[0-9]/', $value)) {
            $fail('El nombre no puede contener números.');
            return;
        }

        if (!preg_match('/^[a-zA-Z\s]+$/', $value)) {
            $fail('El nombre solo debe contener letras y espacios. No se permiten números ni caracteres especiales.');
            return;
        }
    }
}
