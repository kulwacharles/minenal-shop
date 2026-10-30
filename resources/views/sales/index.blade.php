@extends('layouts.app')
@section('title', 'Sales')
@section('page-title', 'Sales')
@section('page-actions')
  <a href="{{ route('sales.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> New Sale</a>
@endsection
@section('content')
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-3"><input type="date" name="from" value="{{ request('from') }}" class="form-control"></div>
    <div class="col-md-3"><input type="date" name="to" value="{{ request('to') }}" class="form-control"></div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">All statuses</option>
        @foreach (['paid', 'pending', 'refunded'] as $s)
          <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3"><button class="btn btn-outline-secondary w-100">Filter</button></div>
  </form>

  <div class="card">
    <table class="table mb-0 align-middle">
      <thead><tr><th>Invoice</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr></thead>
      <tbody>
        @forelse ($sales as $s)
          <tr>
            <td><a href="{{ route('sales.show', $s) }}" class="fw-semibold text-decoration-none" style="color:var(--accent)">{{ $s->invoice_no }}</a></td>
            <td>{{ $s->customer->name ?? 'Walk-in' }}</td>
            <td>{{ $s->items()->count() }}</td>
            <td class="fw-semibold">${{ number_format($s->total, 2) }}</td>
            <td class="small">{{ ucfirst($s->payment_method) }}</td>
            <td>
              @php $map = ['paid'=>'success','pending'=>'warning','refunded'=>'danger']; @endphp
              <span class="badge badge-soft-{{ $map[$s->status] ?? 'info' }}">{{ ucfirst($s->status) }}</span>
            </td>
            <td class="small text-muted">{{ $s->sold_at->format('M d, H:i') }}</td>
            <td class="text-end">
              <form method="POST" action="{{ route('sales.destroy', $s) }}" onsubmit="return confirm('Refund this sale? Stock will be restored.')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-arrow-counterclockwise"></i></button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center py-4 text-muted">No sales yet</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-3">{{ $sales->links() }}</div>
@endsection