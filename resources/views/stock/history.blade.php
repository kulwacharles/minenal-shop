@extends('layouts.app')
@section('title', 'Stock History — ' . $mineral->name)
@section('page-title', 'Stock History — ' . $mineral->name)

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('stock.index') }}">Stock</a></li>
      <li class="breadcrumb-item active">{{ $mineral->name }}</li>
    </ol>
  </nav>
@endsection

@section('content')
  <div class="card p-3 mb-3">
    <div class="d-flex justify-content-between flex-wrap gap-3">
      <div>
        <div class="fw-bold">{{ $mineral->name }}</div>
        <div class="small" style="color:var(--app-muted)">{{ $mineral->sku }}</div>
      </div>
      <div class="text-md-end">
        <div class="small" style="color:var(--app-muted)">Current stock</div>
        <div class="fw-bold fs-5 {{ $mineral->is_low_stock ? 'text-danger' : '' }}">
          {{ $mineral->stock_qty }} {{ $mineral->unit }}
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive">
      <table class="table mb-0 align-middle">
        <thead>
          <tr>
            <th>Date</th>
            <th>Type</th>
            <th class="text-end">Qty</th>
            <th>By</th>
            <th>Notes</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($movements as $mv)
            <tr>
              <td class="small" style="color:var(--app-muted)">
                {{ $mv->created_at->format('M d, Y H:i') }}
              </td>
              <td>
                @php
                  $map = [
                    'sale'       => 'danger',
                    'purchase'   => 'success',
                    'adjustment' => 'info',
                    'return'     => 'warning',
                  ];
                  $cls = $map[$mv->type] ?? 'info';
                @endphp
                <span class="badge badge-soft-{{ $cls }}">{{ $mv->type }}</span>
              </td>
              <td class="text-end fw-semibold {{ $mv->quantity > 0 ? 'text-success' : 'text-danger' }}">
                {{ $mv->quantity > 0 ? '+' : '' }}{{ $mv->quantity }}
              </td>
              <td class="small">{{ $mv->user->name ?? 'system' }}</td>
              <td class="small" style="color:var(--app-muted)">{{ $mv->notes }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-4" style="color:var(--app-muted)">
                No stock movements yet.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $movements->links() }}</div>
@endsection