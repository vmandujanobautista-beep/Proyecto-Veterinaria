<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidStrictEmailRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value)) {
            $fail('El correo no es válido.');
            return;
        }

        if (trim($value) !== $value) {
            $fail('El correo no debe contener espacios al inicio ni al final.');
            return;
        }

        if (preg_match('/\s/', $value)) {
            $fail('El correo no puede contener espacios.');
            return;
        }

        if (strtolower($value) !== $value) {
            $fail('El correo debe estar en minúsculas.');
            return;
        }

        if (preg_match('/[ñáéíóúÁÉÍÓÚÑ]/u', $value)) {
            $fail('El correo no puede contener eñes ni acentos.');
            return;
        }

        if (preg_match('/[\(\)\[\]\{\}<>"\':;\\\\]/', $value)) {
            $fail('El correo contiene caracteres especiales no permitidos.');
            return;
        }

        if (substr_count($value, '@') !== 1) {
            $fail('El correo debe contener exactamente un símbolo @.');
            return;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('El formato del correo o dominio no es válido.');
            return;
        }
    }
}
