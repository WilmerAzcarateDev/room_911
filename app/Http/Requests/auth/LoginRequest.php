<?php

namespace App\Http\Requests\auth;

use App\enums\LoginStatus;
use App\Models\LoginAttempt;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
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
        $user = User::where('document',$this->input('document'))->first();
        if(!$user){
            LoginAttempt::create([
                'ip'=>$this->ip()
            ]);
            return false;
        };
        if(!$user->hasRole('admin_room_911')){
            LoginAttempt::create([
                'status'=>LoginStatus::FAILED,
                'ip'=>$this->ip(),
                'user_id'=>$user->id
            ]);
            return false;
        }
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
            'document' => ['required'],
            'password' => ['required','string']
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('document', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            LoginAttempt::create([
                'status' => LoginStatus::FAILED,
                'ip' => $this->ip(),
                'user_id' => User::where('document',$this->input('document'))->first()->id
            ]);

            throw ValidationException::withMessages([
                'document' => trans('auth.failed'),
            ]);
        }

        LoginAttempt::create([
            'status' => LoginStatus::SUCCESS,
            'ip' => $this->ip(),
            'user_id' => Auth::id()
        ]);

        RateLimiter::clear($this->throttleKey());
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
            'document' => trans('auth.throttle', [
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
        return Str::transliterate(Str::lower($this->string('document')).'|'.$this->ip());
    }
}
