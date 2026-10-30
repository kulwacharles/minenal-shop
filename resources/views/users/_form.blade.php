@php $u = $user ?? null; @endphp
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input name="name" value="{{ old('name', $u->name ?? '') }}" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Email <span class="text-danger">*</span></label>
    <input name="email" type="email" value="{{ old('email', $u->email ?? '') }}" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">
      Password {{ $u ? '(leave blank to keep current)' : '*' }}
    </label>
    <input name="password" type="password" class="form-control" {{ $u ? '' : 'required' }}>
  </div>
  <div class="col-md-6">
    <label class="form-label">Confirm Password {{ $u ? '' : '*' }}</label>
    <input name="password_confirmation" type="password" class="form-control" {{ $u ? '' : 'required' }}>
  </div>
  <div class="col-md-6">
    <label class="form-label">Role <span class="text-danger">*</span></label>
    <select name="role" class="form-select" required>
      <option value="">Select role…</option>
      @foreach ($roles as $r)
        <option value="{{ $r->name }}"
          @selected(old('role', $u?->roles->first()?->name) === $r->name)>
          {{ ucfirst(str_replace('-', ' ', $r->name)) }}
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Branch</label>
    <select name="branch_id" class="form-select">
      <option value="">All branches (super-admin only)</option>
      @foreach ($branches as $b)
        <option value="{{ $b->id }}"
          @selected((int) old('branch_id', $u->branch_id ?? 0) === $b->id)>
          {{ $b->name }}
        </option>
      @endforeach
    </select>
    <div class="small mt-1" style="color:var(--app-muted)">
      Leave blank for super-admin. Others must be assigned to a branch.
    </div>
  </div>
  <div class="col-12">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="is_active" value="1"
             @checked(old('is_active', $u->is_active ?? true))>
      <label class="form-check-label">Active (can log in)</label>
    </div>
  </div>
</div>