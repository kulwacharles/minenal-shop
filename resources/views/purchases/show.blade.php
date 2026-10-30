@extends('layouts.app')
@section('title', 'Purchase ' . $purchase->reference_no)
@section('page-title', 'Purchase ' . $purchase->reference_no)

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
      <li class="breadcrumb-item active">{{ $purchase->reference_no }}</li>
    </ol>
  </nav>
@endsection

@section('page-actions')
  @if ($purchase->status === 'pending')
    <form method="POST" action="{{ route('purchases.receive', $purchase) }}"
          class="d-inline"
          onsubmit="return confirm('Receive this purchase? Stock will be added to all items.')">
      @csrf
      <button class="btn btn-success">
        <i class="bi bi-box-arrow-in-down me-1"></i> Receive & Add Stock
      </button>
    </form>
  @endif
  <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">Back</a>
@endsection

@section('content')
  <div class="card p-4">

    <div class="d-flex justify-content-between flex-wrap gap-3 mb-4">
      <div>
        <div class="fw-bold fs-5">{{ $purchase->reference_no }}</div>
        <div class="small" style="color:var(--app-muted)">
          {{ $purchase->purchased_at->format('F d, Y H:i') }}
        </div>
      </div>

      <div class="text-md-end">
        @php
          $map = [
            'pending'   => 'warning',
            'received'  => 'success',
            'cancelled' => 'danger',
          ];
          $cls = $map[$purchase->status] ?? 'info';
        @endphp
        <span class="badge badge-soft-{{ $cls }} fs-6">{{ ucfirst($purchase->status) }}</span>
      </div>
    </div>

    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="small" style="color:var(--app-muted)">Supplier</div>
        <div class="fw-semibold">{{ $purchase->supplier->name ?? '—' }}</div>
        @if ($purchase->supplier?->contact_name)
          <div class="small">Attn: {{ $purchase->supplier->contact_name }}</div>
        @endif
      </div>
      <div class="col-md-6 text-md-end">
        <div class="small" style="color:var(--app-muted)">Created by</div>
        <div class="fw-semibold">{{ $purchase->user->name ?? '—' }}</div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Item</th>
            <th class="text-end">Qty</th>
            <th class="text-end">Unit Cost</th>
            <th class="text-end">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($purchase->items as $item)
            <tr>
              <td>
                @if ($item->mineral)
                  <a href="{{ route('minerals.show', $item->mineral) }}"
                     class="text-decoration-none" style="color:var(--app-text)">
                    {{ $item->mineral->name }}
                  </a>
                @else
                  <span class="text-muted">Deleted mineral</span>
                @endif
              </td>
              <td class="text-end">{{ $item->quantity }}</td>
              <td class="text-end">${{ number_format($item->unit_cost, 2) }}</td>
              <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="ms-auto" style="max-width:320px">
      <hr>
      <div class="d-flex justify-content-between fw-bold fs-5">
        <span>Total</span>
        <span>${{ number_format($purchase->total, 2) }}</span>
      </div>
    </div>

    @if ($purchase->notes)
      <div class="mt-4 pt-3" style="border-top:1px solid var(--app-border-soft)">
        <div class="small fw-semibold mb-1">Notes</div>
        <div class="small" style="color:var(--app-muted)">{{ $purchase->notes }}</div>
      </div>
    @endif

  </div>
@endsection