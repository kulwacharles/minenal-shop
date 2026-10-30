@php $c = $customer ?? null; @endphp
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Name <span class="text-danger">*</span></label>
    <input name="name" value="{{ old('name', $c->name ?? '') }}" class="form-control" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input name="email" type="email" value="{{ old('email', $c->email ?? '') }}" class="form-control">
  </div>
  <div class="col-md-6">
    <label class="form-label">Phone</label>
    <input name="phone" value="{{ old('phone', $c->phone ?? '') }}" class="form-control">
  </div>
  <div class="col-12">
    <label class="form-label">Address</label>
    <textarea name="address" rows="2" class="form-control">{{ old('address', $c->address ?? '') }}</textarea>
  </div>
  <div class="col-12">
    <label class="form-label">Notes</label>
    <textarea name="notes" rows="3" class="form-control">{{ old('notes', $c->notes ?? '') }}</textarea>
  </div>
</div>