<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="success-message">
            {{ session('status') }}
        </div>
    @endif

    <h2 class="auth-title">
        {{ __('Sign in to your account') }}
    </h2>

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email"
                class="form-input {{ $errors->get('email') ? 'error' : '' }}"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username" />
            @if ($errors->get('email'))
                <div class="error-message">
                    {{ $errors->get('email')[0] }}
                </div>
            @endif
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password"
                class="form-input {{ $errors->get('password') ? 'error' : '' }}"
                type="password"
                name="password"
                required
                autocomplete="current-password" />
            @if ($errors->get('password'))
                <div class="error-message">
                    {{ $errors->get('password')[0] }}
                </div>
            @endif
        </div>

        <!-- Remember Me -->
        <div class="remember-section">
            <label for="remember_me" class="remember-label">
                <input id="remember_me"
                    type="checkbox"
                    class="form-checkbox"
                    name="remember">
                <span class="remember-text">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary">
            {{ __('Log in') }}
        </button>
    </form>
</x-guest-layout>
