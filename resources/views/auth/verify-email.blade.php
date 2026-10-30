@extends('layouts.guest')
@section('title', 'Verify email')

@section('content')
  <div class="card">
    <h1>Verify your email</h1>
    <p class="auth-subtitle">
      We sent a verification link to
      <strong style="color:var(--app-text)">{{ auth()->user()->email }}</strong>.
      Click the link to activate your account.
    </p>

    @if (session('status') == 'verification-link-sent')
      <div class="alert alert-success py-2 px-3 small">
        A new verification link has been sent.
      </div>
    @endif

    <div class="d-flex gap-2 mt-3">
      <form method="POST" action="{{ route('verification.send') }}" class="flex-fill">
        @csrf
        <button class="btn btn-primary w-100">
          <i class="bi bi-arrow-clockwise me-1"></i> Resend link
        </button>
      </form>

      <form method="POST" action="{{ route('logout') }}" class="flex-fill">
        @csrf
        <button class="btn btn-outline-secondary w-100">
          <i class="bi bi-box-arrow-right me-1"></i> Log out
        </button>
      </form>
    </div>
  </div>
@endsection