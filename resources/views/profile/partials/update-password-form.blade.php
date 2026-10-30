<h2 class="fs-6 fw-bold mb-1">Update password</h2>
<p class="small mb-3" style="color:var(--app-muted)">
  Use a long, random password to stay secure.
</p>

<form method="POST" action="{{ route('password.update') }}">
  @csrf
  @method('put')

  <div class="row g-3">
    <div class="col-md-4">
      <label for="current_password" class="form-label">Current password</label>
      <input id="current_password" name="current_password" type="password"
             class="form-control" autocomplete="current-password">
      @if ($errors->updatePassword->get('current_password'))
        <div class="text-danger small mt-1">{{ $errors->updatePassword->first('current_password') }}</div>
      @endif
    </div>
    <div class="col-md-4">
      <label for="password" class="form-label">New password</label>
      <input id="password" name="password" type="password"
             class="form-control" autocomplete="new-password">
      @if ($errors->updatePassword->get('password'))
        <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password') }}</div>
      @endif
    </div>
    <div class="col-md-4">
      <label for="password_confirmation" class="form-label">Confirm</label>
      <input id="password_confirmation" name="password_confirmation" type="password"
             class="form-control" autocomplete="new-password">
    </div>
  </div>

  <div class="mt-3 d-flex align-items-center gap-3">
    <button class="btn btn-primary">Update password</button>
    @if (session('status') === 'password-updated')
      <span class="small text-success"><i class="bi bi-check2-circle me-1"></i> Updated</span>
    @endif
  </div>
</form>