@php $b = $branch ?? null; @endphp
<div class="row g-3">
  <div class="col-md-8">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input name="name" value="{{ old('name', $b->name ?? '') }}" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Code <span class="text-danger">*</span></label>
    <input name="code" value="{{ old('code', $b->code ?? '') }}" class="form-control" required
           placeholder="MAIN, NORTH, etc.">
  </div>
  <div class="col-md-6">
    <label class="form-label">Phone</label>
    <input name="phone" value="{{ old('phone', $b->phone ?? '') }}" class="form-control">
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input name="email" type="email" value="{{ old('email', $b->email ?? '') }}" class="form-control">
  </div>
  <div class="col-12">
    <label class="form-label">Address</label>
    <textarea name="address" rows="2" class="form-control">{{ old('address', $b->address ?? '') }}</textarea>
  </div>
  <div class="col-12">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="is_active" value="1"
             @checked(old('is_active', $b->is_active ?? true))>
      <label class="form-check-label">Active</label>
    </div>
  </div>
</div>