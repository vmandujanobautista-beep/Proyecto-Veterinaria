<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidEmailRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || trim($value) === '') {
            $fail('El correo electrónico es obligatorio.');
            return;
        }

        if (preg_match('/^\s|\s$/', $value) || preg_match('/\s/', $value)) {
            $fail('El correo no debe tener espacios ni al inicio, ni al final, ni en medio.');
            return;
        }

        if (preg_match('/[A-Z]/', $value)) {
            $fail('El correo no debe contener letras mayúsculas (todo debe ser en minúsculas).');
            return;
        }

        if (preg_match('/[ñÑáéíóúÁÉÍÓÚ]/u', $value)) {
            $fail('El correo no debe contener eñes ni acentos.');
            return;
        }

        if (preg_match('/[\(\)\[\]\{\}<>":;\\\\]/', $value)) {
            $fail('El correo contiene caracteres especiales no permitidos (como paréntesis, corchetes, llaves, comillas, etc.).');
            return;
        }

        $atCount = substr_count($value, '@');
        if ($atCount === 0) {
            $fail('El correo debe contener exactamente un símbolo @.');
            return;
        }
        if ($atCount > 1) {
            $fail('El correo no puede tener más de una @.');
            return;
        }

        if (!preg_match('/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/', $value)) {
            $fail('El correo debe tener un formato y dominio válido (.com, .es, .org, etc.).');
            return;
        }
    }
}
