<?php

namespace App\Http\Requests\Auth;

use App\Http\Middleware\LoginRateLimit;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:255'
            ],
            'remember' => ['nullable', 'boolean']
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe proporcionar un correo electrónico válido.',
            'email.regex' => 'El formato del correo electrónico no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitizar email
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->input('email')))
            ]);
        }

        // Log del intento de login
        Log::info('Intento de login iniciado', [
            'ip' => $this->ip(),
            'user_agent' => $this->userAgent(),
            'email' => $this->input('email'),
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('email', 'password');
        $remember = $this->boolean('remember');

        // Intentar autenticación
        if (! Auth::attempt($credentials, $remember)) {
            // Incrementar contador de intentos fallidos
            RateLimiter::hit($this->throttleKey());
            LoginRateLimit::incrementFailedAttempts($this);

            // Log del intento fallido
            Log::warning('Intento de login fallido', [
                'ip' => $this->ip(),
                'user_agent' => $this->userAgent(),
                'email' => $this->input('email'),
                'timestamp' => now()->toISOString(),
                'attempts_remaining' => 5 - RateLimiter::attempts($this->throttleKey())
            ]);

            throw ValidationException::withMessages([
                'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ]);
        }

        // Login exitoso - limpiar intentos fallidos
        RateLimiter::clear($this->throttleKey());
        LoginRateLimit::clearFailedAttempts($this);

        // Log del login exitoso
        Log::info('Login exitoso', [
            'ip' => $this->ip(),
            'user_agent' => $this->userAgent(),
            'email' => $this->input('email'),
            'user_id' => Auth::id(),
            'remember' => $remember,
            'timestamp' => now()->toISOString()
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
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

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
