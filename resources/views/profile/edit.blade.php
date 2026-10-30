@extends('layouts.app')
@section('title', 'Profile')
@section('page-title', 'Profile')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Profile</li>
    </ol>
  </nav>
@endsection

@section('content')
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-4 mb-3">
        @include('profile.partials.update-profile-information-form')
      </div>

      <div class="card p-4 mb-3">
        @include('profile.partials.update-password-form')
      </div>

      <div class="card p-4 border-danger" style="border-width:1px!important;">
        @include('profile.partials.delete-user-form')
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card p-4 text-center">
        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=d4a24c&color=fff&size=128"
             class="rounded-circle mx-auto mb-3" width="88" height="88" alt="">
        <div class="fw-bold">{{ auth()->user()->name }}</div>
        <div class="small" style="color:var(--app-muted)">{{ auth()->user()->email }}</div>

        <hr>

        <div class="d-flex justify-content-between small"><span>Member since</span>
          <span>{{ auth()->user()->created_at->format('M Y') }}</span>
        </div>
        <div class="d-flex justify-content-between small"><span>Email status</span>
          @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && auth()->user()->hasVerifiedEmail())
            <span class="text-success">Verified</span>
          @else
            <span class="text-warning">Unverified</span>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection