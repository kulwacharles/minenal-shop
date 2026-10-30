@extends('layouts.guest')
@section('title', 'Reset password')

@section('content')
  <div class="card">
    <h1>Reset password</h1>
    <p class="auth-subtitle">Choose a new password for your account.</p>

    @if ($errors->any())
      <div class="alert alert-danger py-2 px-3 small">
        @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
      </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}">
      @csrf
      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email"
               value="{{ old('email', $request->email) }}"
               class="form-control" required autofocus autocomplete="username">
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">New password</label>
        <input id="password" name="password" type="password"
               class="form-control" required autocomplete="new-password">
      </div>

      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm new password</label>
        <input id="password_confirmation" name="password_confirmation" type="password"
               class="form-control" required autocomplete="new-password">
      </div>

      <button class="btn btn-primary w-100">
        <i class="bi bi-shield-check me-1"></i> Reset password
      </button>
    </form>
  </div>
@endsection