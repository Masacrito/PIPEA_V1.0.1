<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'El formato del correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }

    /**
     * Autenticar al usuario con throttle y verificación de cuenta activa.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // 1. Verificar credenciales
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Las credenciales proporcionadas no son correctas.',
            ]);
        }

        // 2. Verificar que la cuenta esté activa
        if (! Auth::user()->activo) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Tu cuenta se encuentra desactivada. Contacta al administrador del sistema.',
            ]);
        }

        // 3. Verificar que tenga rol asignado
        if (! Auth::user()->id_rol) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Tu cuenta no tiene un rol asignado. Contacta al administrador.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Verificar que no se haya excedido el límite de intentos.
     * Máximo 5 intentos por IP + email. Bloqueo de 1 minuto.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());
        $minutos = ceil($seconds / 60);

        throw ValidationException::withMessages([
            'email' => $seconds < 60
                ? "Demasiados intentos fallidos. Intenta de nuevo en {$seconds} segundos."
                : "Demasiados intentos fallidos. Intenta de nuevo en {$minutos} minuto(s).",
        ]);
    }

    /**
     * Clave única de throttle: email + IP.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
