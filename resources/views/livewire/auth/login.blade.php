<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<!-- Background avec gradient et formes flottantes -->
<div class="gradient-bg">
    <!-- Formes flottantes animées -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <!-- Conteneur principal -->
    <div class="min-h-screen flex items-center justify-center px-4 relative z-10">
        <div class="login-container fade-in">
            
            <!-- Logo/Brand Area -->
            <div class="text-center mb-8">
                <div class="logo-container">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="auth-header">
                    <h1>{{ __('Log in to your account') }}</h1>
                    <p>{{ __('Enter your email and password below to log in') }}</p>
                </div>
            </div>

            <!-- Conteneur du formulaire avec effet de verre -->
            <div class="glass-effect p-8 slide-up">
                <div class="flex flex-col gap-6">
                    
                    <!-- Session Status -->
                    <x-auth-session-status class="success-message" :status="session('status')" />

                    <form wire:submit="login" class="flex flex-col gap-6">
                        <!-- Email Address -->
                        <div class="input-enhanced">
                            <label for="email">{{ __('Email address') }}</label>
                            <div class="relative">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                </svg>
                                <flux:input
                                    wire:model="email"
                                    type="email"
                                    id="email"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    placeholder="email@example.com"
                                    class="!pl-10"
                                />
                            </div>
                            @error('email')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="input-enhanced">
                            <div class="flex justify-between items-center mb-2">
                                <label for="password">{{ __('Password') }}</label>
                                @if (Route::has('password.request'))
                                    <flux:link class="forgot-password" :href="route('password.request')" wire:navigate>
                                        {{ __('Forgot your password?') }}
                                    </flux:link>
                                @endif
                            </div>
                            <div class="relative">
                                <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <flux:input
                                    wire:model="password"
                                    type="password"
                                    id="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="{{ __('Password') }}"
                                    viewable
                                    class="!pl-10"
                                />
                            </div>
                            @error('password')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="checkbox-container">
                            <flux:checkbox 
                                wire:model="remember" 
                                id="remember" 
                                class="checkbox-enhanced"
                            />
                            <label for="remember" class="checkbox-label">{{ __('Remember me') }}</label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end">
                            <flux:button 
                                variant="primary" 
                                type="submit" 
                                class="btn-primary"
                                wire:loading.attr="disabled"
                            >
                                <span wire:loading.remove>{{ __('Log in') }}</span>
                                <span wire:loading class="flex items-center gap-2">
                                    <span class="loading"></span>
                                    {{ __('Logging in...') }}
                                </span>
                            </flux:button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>