@extends('layouts.app')
@section('title', 'Invoice ' . $sale->invoice_no)
@section('page-title', 'Invoice ' . $sale->invoice_no)

@section('page-actions')
  <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Back</a>
@endsection

@section('content')
  <div class="card p-4">
    <div class="d-flex justify-content-between mb-4">
      <div>
        <h5 class="mb-1">Invoice {{ $sale->invoice_no }}</h5>
        <div class="small text-muted">{{ $sale->sold_at->format('F d, Y H:i') }}</div>
      </div>
      <div class="text-end">
        <div class="small text-muted">Customer</div>
        <div class="fw-semibold">{{ $sale->customer->name ?? 'Walk-in' }}</div>
      </div>
    </div>

    <table class="table">
      <thead><tr><th>Item</th><th class="text-end">Qty</th><th class="text-end">Unit</th><th class="text-end">Subtotal</th></tr></thead>
      <tbody>
        @foreach ($sale->items as $item)
          <tr>
            <td>{{ $item->mineral->name }}</td>
            <td class="text-end">{{ $item->quantity }}</td>
            <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
            <td class="text-end">${{ number_format($item->subtotal, 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="ms-auto" style="max-width:320px">
      <div class="d-flex justify-content-between"><span>Subtotal</span><span>${{ number_format($sale->subtotal, 2) }}</span></div>
      <div class="d-flex justify-content-between"><span>Discount</span><span>-${{ number_format($sale->discount, 2) }}</span></div>
      <div class="d-flex justify-content-between"><span>Tax</span><span>+${{ number_format($sale->tax, 2) }}</span></div>
      <hr>
      <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span>${{ number_format($sale->total, 2) }}</span></div>
    </div>
  </div>
@endsection