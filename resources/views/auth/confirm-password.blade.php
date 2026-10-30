@extends('layouts.guest')
@section('title', 'Confirm password')

@section('content')
  <div class="card">
    <h1>Confirm password</h1>
    <p class="auth-subtitle">
      This is a secure area. Please confirm your password before continuing.
    </p>

    @if ($errors->any())
      <div class="alert alert-danger py-2 px-3 small">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.confirm') }}">
      @csrf
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input id="password" name="password" type="password"
               class="form-control" required autofocus autocomplete="current-password">
      </div>
      <button class="btn btn-primary w-100">
        <i class="bi bi-shield-check me-1"></i> Confirm
      </button>
    </form>
  </div>
@endsection