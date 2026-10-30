@extends('layouts.guest')
@section('title', 'Create account')

@section('content')
  <div class="card">
    <h1>Create account</h1>
    <p class="auth-subtitle">Start managing your mineral shop in minutes.</p>

    @if ($errors->any())
      <div class="alert alert-danger py-2 px-3 small">
        @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name') }}"
               class="form-control" required autofocus autocomplete="name"
               placeholder="Jane Doe">
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}"
               class="form-control" required autocomplete="username"
               placeholder="you@example.com">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" type="password"
               class="form-control" required autocomplete="new-password"
               placeholder="At least 8 characters">
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm password</label>
        <input id="password_confirmation" name="password_confirmation" type="password"
               class="form-control" required autocomplete="new-password"
               placeholder="Repeat password">
      </div>

      <button type="submit" class="btn btn-primary w-100">
        <i class="bi bi-person-plus me-1"></i> Create account
      </button>
    </form>

    <div class="auth-divider">Already have an account?</div>

    <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
      Sign in instead
    </a>
  </div>
@endsection