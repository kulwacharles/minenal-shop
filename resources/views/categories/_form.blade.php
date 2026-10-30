@php $c = $category ?? null; @endphp
<div class="row g-3">
  <div class="col-md-8">
    <label class="form-label">Name</label>
    <input name="name" value="{{ old('name', $c->name ?? '') }}" class="form-control" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Color</label>
    <input name="color" type="color" value="{{ old('color', $c->color ?? '#d4a24c') }}" class="form-control form-control-color">
  </div>
  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description', $c->description ?? '') }}</textarea>
  </div>
</div>