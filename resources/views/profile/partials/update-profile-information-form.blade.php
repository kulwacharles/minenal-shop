<h2 class="fs-6 fw-bold mb-1">Profile information</h2>
<p class="small mb-3" style="color:var(--app-muted)">
  Update your account's name and email address.
</p>

<form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="d-none">
  @csrf
</form>

<form method="POST" action="{{ route('profile.update') }}">
  @csrf
  @method('patch')

  <div class="row g-3">
    <div class="col-md-6">
      <label for="name" class="form-label">Name</label>
      <input id="name" name="name" type="text"
             value="{{ old('name', $user->name) }}"
             class="form-control" required autocomplete="name">
    </div>
    <div class="col-md-6">
      <label for="email" class="form-label">Email</label>
      <input id="email" name="email" type="email"
             value="{{ old('email', $user->email) }}"
             class="form-control" required autocomplete="username">
    </div>
  </div>

  @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
    <div class="alert alert-warning py-2 px-3 small mt-3 mb-0">
      Your email is unverified.
      <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">
        Click here to re-send the verification email.
      </button>
      @if (session('status') === 'verification-link-sent')
        <div class="mt-1 text-success small">Verification link sent.</div>
      @endif
    </div>
  @endif

  <div class="mt-3 d-flex align-items-center gap-3">
    <button class="btn btn-primary">Save</button>
    @if (session('status') === 'profile-updated')
      <span class="small text-success"><i class="bi bi-check2-circle me-1"></i> Saved</span>
    @endif
  </div>
</form>