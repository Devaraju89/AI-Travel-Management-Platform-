@extends('layouts.app')
@section('title', 'Log In - TravelMate')
@section('content')

<style>
    .auth-container {
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1.5rem;
        background-color: var(--bg-body);
    }
    .auth-card {
        background: var(--surface);
        width: 100%;
        max-width: 440px;
        border-radius: 12px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--border);
        padding: 2.5rem 2rem;
    }
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .auth-header h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }
    .auth-header p {
        color: var(--text-muted);
        font-size: 0.95rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
    }
    .form-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.95rem;
        outline: none;
        transition: 0.2s;
        font-family: 'Inter', sans-serif;
    }
    .form-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
    }
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .form-check input {
        width: 16px;
        height: 16px;
        accent-color: var(--primary);
        cursor: pointer;
    }
    .form-check label {
        font-size: 0.9rem;
        color: var(--text-muted);
        cursor: pointer;
    }
    .auth-btn {
        width: 100%;
        padding: 0.8rem;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.2s;
    }
    .auth-btn:hover {
        background: var(--primary-hover);
    }
    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    .auth-footer a {
        color: var(--primary);
        font-weight: 600;
    }
    .auth-footer a:hover {
        text-decoration: underline;
    }
    .input-error {
        color: #ef4444;
        font-size: 0.8rem;
        margin-top: 0.25rem;
        display: block;
    }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p>Log in to your TravelMate account to manage your trips.</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="alert alert-success" style="margin-bottom: 1.5rem; font-size: 0.85rem; padding: 0.75rem;">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" style="margin-bottom: 1.5rem; font-size: 0.85rem; padding: 0.75rem; background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; border-radius: 8px;">
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
                @error('email')
                    <span class="input-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label for="password" class="form-label" style="margin: 0;">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size: 0.8rem; color: var(--primary); font-weight: 500;">Forgot password?</a>
                    @endif
                </div>
                <input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" style="margin-top: 0.5rem;">
                @error('password')
                    <span class="input-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="form-group form-check">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Keep me logged in</label>
            </div>

            <button type="submit" class="auth-btn">
                Log In
            </button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Sign up</a>
        </div>
    </div>
</div>

@endsection
