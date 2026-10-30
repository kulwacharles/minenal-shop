@extends('layouts.guest')
@section('title', 'Forgot password')

@section('content')
  <div class="card">
    <h1>Forgot password?</h1>
    <p class="auth-subtitle">Enter your email and we'll send you a reset link.</p>

    @if (session('status'))
      <div class="alert alert-success py-2 px-3 small">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger py-2 px-3 small">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}"
               class="form-control" required autofocus
               placeholder="you@example.com">
      </div>
      <button class="btn btn-primary w-100">
        <i class="bi bi-envelope me-1"></i> Email reset link
      </button>
    </form>

    <div class="auth-divider">or</div>

    <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
      Back to sign in
    </a>
  </div>
@endsection