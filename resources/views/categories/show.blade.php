@extends('layouts.app')
@section('title', $mineral->name)
@section('page-title', $mineral->name)

@section('page-actions')
  <a href="{{ route('minerals.edit', $mineral) }}" class="btn btn-outline-secondary"><i class="bi bi-pencil me-1"></i> Edit</a>
@endsection

@section('content')
  <div class="row g-3">
    <div class="col-lg-4">
      <div class="card p-3 text-center">
        <img src="{{ $mineral->image_url }}" class="rounded mx-auto mb-3" width="180" height="180" style="object-fit:cover">
        <div class="fs-5 fw-bold">${{ number_format($mineral->sale_price, 2) }}</div>
        <div class="small" style="color:var(--app-muted)">Cost: ${{ number_format($mineral->cost_price, 2) }}</div>
        <hr>
        <div class="d-flex justify-content-between small"><span>Stock</span>
          <span class="{{ $mineral->is_low_stock ? 'text-danger fw-semibold' : '' }}">{{ $mineral->stock_qty }} {{ $mineral->unit }}</span>
        </div>
        <div class="d-flex justify-content-between small"><span>Min</span><span>{{ $mineral->min_stock_qty }}</span></div>
        <div class="d-flex justify-content-between small"><span>Category</span><span>{{ $mineral->category->name }}</span></div>
        <div class="d-flex justify-content-between small"><span>Supplier</span><span>{{ $mineral->supplier->name ?? '—' }}</span></div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">Stock Movement History</div>
        <div class="table-responsive">
          <table class="table mb-0 align-middle">
            <thead><tr><th>Date</th><th>Type</th><th>Qty</th><th>By</th><th>Notes</th></tr></thead>
            <tbody>
              @forelse ($movements as $mv)
                <tr>
                  <td class="small">{{ $mv->created_at->format('M d, H:i') }}</td>
                  <td><span class="badge badge-soft-{{ $mv->quantity > 0 ? 'success' : 'danger' }}">{{ $mv->type }}</span></td>
                  <td class="fw-semibold {{ $mv->quantity > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $mv->quantity > 0 ? '+' : '' }}{{ $mv->quantity }}
                  </td>
                  <td class="small">{{ $mv->user->name ?? 'system' }}</td>
                  <td class="small text-muted">{{ $mv->notes }}</td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center py-4 small text-muted">No movements yet</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="mt-3">{{ $movements->links() }}</div>
    </div>
  </div>
@endsection