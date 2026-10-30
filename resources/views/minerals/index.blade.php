@extends('layouts.app')
@section('title', 'Minerals')
@section('page-title', 'Minerals')

@section('page-actions')
  <a href="{{ route('minerals.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> New Mineral
  </a>
@endsection

@section('content')
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name or SKU…">
    </div>
    <div class="col-md-3">
      <select name="category" class="form-select">
        <option value="">All categories</option>
        @foreach ($categories as $c)
          <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" name="low_stock" value="1" @checked(request('low_stock'))>
        <label class="form-check-label">Only low stock</label>
      </div>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
  </form>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr><th></th><th>Name</th><th>SKU</th><th>Category</th><th>Stock</th><th>Cost</th><th>Price</th><th></th></tr>
        </thead>
        <tbody>
          @forelse ($minerals as $m)
            <tr>
              <td style="width:56px">
                <img src="{{ $m->image_url }}" width="40" height="40" class="rounded" style="object-fit:cover">
              </td>
              <td>
                <a href="{{ route('minerals.show', $m) }}" class="fw-semibold text-decoration-none" style="color:var(--app-text)">{{ $m->name }}</a>
                @unless ($m->is_active)<span class="badge badge-soft-danger ms-1">Inactive</span>@endunless
              </td>
              <td class="small" style="color:var(--app-muted)">{{ $m->sku }}</td>
              <td>{{ $m->category->name }}</td>
              <td>
                <span class="{{ $m->is_low_stock ? 'text-danger fw-semibold' : '' }}">{{ $m->stock_qty }}</span>
                <span class="small text-muted">{{ $m->unit }}</span>
              </td>
              <td>${{ number_format($m->cost_price, 2) }}</td>
              <td class="fw-semibold">${{ number_format($m->sale_price, 2) }}</td>
              <td class="text-end">
                <a href="{{ route('minerals.edit', $m) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('minerals.destroy', $m) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center py-4" style="color:var(--app-muted)">No minerals found</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $minerals->links() }}</div>
@endsection