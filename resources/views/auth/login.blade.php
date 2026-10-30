@extends('layouts.guest')
@section('title', 'Sign in')

@section('content')
  <div class="card">
    <h1>Sign in</h1>
    <p class="auth-subtitle">Enter your credentials to access the dashboard.</p>

    @if (session('status'))
      <div class="alert alert-success py-2 px-3 small">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger py-2 px-3 small">
        @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}"
               class="form-control" required autofocus autocomplete="username"
               placeholder="you@example.com">
      </div>

      <div class="mb-2">
        <div class="d-flex justify-content-between">
          <label for="password" class="form-label">Password</label>
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="small">Forgot?</a>
          @endif
        </div>
        <input id="password" name="password" type="password"
               class="form-control" required autocomplete="current-password"
               placeholder="••••••••">
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Remember me</label>
      </div>

      <button type="submit" class="btn btn-primary w-100">
        <i class="bi bi-box-arrow-in-right me-1"></i> Sign in
      </button>
    </form>

    <div class="auth-divider">New here?</div>

    
  </div>
@endsection