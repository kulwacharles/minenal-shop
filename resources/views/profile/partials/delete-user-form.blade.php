<h2 class="fs-6 fw-bold text-danger mb-1">Delete account</h2>
<p class="small mb-3" style="color:var(--app-muted)">
  Once deleted, all of your data will be permanently removed.
  Please download anything you want to keep first.
</p>

<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
  <i class="bi bi-trash me-1"></i> Delete account
</button>

<div class="modal fade" id="deleteAccountModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')

        <div class="modal-header">
          <h5 class="modal-title">Are you sure?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p class="small mb-3">
            This will permanently delete your account and all associated data.
            Enter your password to confirm.
          </p>
          <label for="delete_password" class="form-label">Password</label>
          <input id="delete_password" name="password" type="password"
                 class="form-control" placeholder="Your current password">
          @if ($errors->userDeletion->get('password'))
            <div class="text-danger small mt-1">{{ $errors->userDeletion->first('password') }}</div>
          @endif
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete my account</button>
        </div>
      </form>
    </div>
  </div>
</div>