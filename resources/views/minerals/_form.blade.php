@php $m = $mineral ?? null; @endphp
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" value="{{ old('name', $m->name ?? '') }}" class="form-control" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">SKU</label>
    <input name="sku" value="{{ old('sku', $m->sku ?? '') }}" class="form-control" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Unit</label>
    <select name="unit" class="form-select">
      @foreach (['piece', 'gram', 'carat', 'kg'] as $u)
        <option @selected(old('unit', $m->unit ?? 'piece') === $u)>{{ $u }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">Category</label>
    <select name="category_id" class="form-select" required>
      <option value="">—</option>
      @foreach ($categories as $c)
        <option value="{{ $c->id }}" @selected((int) old('category_id', $m->category_id ?? 0) === $c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Supplier</label>
    <select name="supplier_id" class="form-select">
      <option value="">—</option>
      @foreach ($suppliers as $s)
        <option value="{{ $s->id }}" @selected((int) old('supplier_id', $m->supplier_id ?? 0) === $s->id)>{{ $s->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">Cost Price</label>
    <input name="cost_price" type="number" step="0.01" min="0" value="{{ old('cost_price', $m->cost_price ?? '0.00') }}" class="form-control" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Sale Price</label>
    <input name="sale_price" type="number" step="0.01" min="0" value="{{ old('sale_price', $m->sale_price ?? '0.00') }}" class="form-control" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Stock Qty</label>
    <input name="stock_qty" type="number" min="0" value="{{ old('stock_qty', $m->stock_qty ?? 0) }}" class="form-control" required>
  </div>
  <div class="col-md-3">
    <label class="form-label">Min Stock Qty</label>
    <input name="min_stock_qty" type="number" min="0" value="{{ old('min_stock_qty', $m->min_stock_qty ?? 5) }}" class="form-control" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Image</label>
    <input name="image" type="file" accept="image/*" class="form-control">
    @if ($m?->image)
      <img src="{{ $m->image_url }}" class="mt-2 rounded" width="80" height="80" style="object-fit:cover">
    @endif
  </div>
  <div class="col-md-6 d-flex align-items-end">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" name="is_active" value="1"
             @checked(old('is_active', $m->is_active ?? true))>
      <label class="form-check-label">Active</label>
    </div>
  </div>

  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description', $m->description ?? '') }}</textarea>
  </div>
</div>