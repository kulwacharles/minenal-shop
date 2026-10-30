@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
  <form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
      <label class="form-label small">From</label>
      <input type="date" name="from" value="{{ $from->toDateString() }}" class="form-control">
    </div>
    <div class="col-md-4">
      <label class="form-label small">To</label>
      <input type="date" name="to" value="{{ $to->toDateString() }}" class="form-control">
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button class="btn btn-primary w-100">Run Report</button>
    </div>
  </form>

  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card p-3">
        <div class="stat-label">Total Sales</div>
        <div class="stat-value">${{ number_format($salesTotal, 2) }}</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <div class="stat-label">Sales Count</div>
        <div class="stat-value">{{ $salesCount }}</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <div class="stat-label">Purchases</div>
        <div class="stat-value">${{ number_format($purchaseTotal, 2) }}</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card p-3">
        <div class="stat-label">Stock Value</div>
        <div class="stat-value">${{ number_format($stockValue, 2) }}</div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">Top Minerals</div>
        <table class="table mb-0">
          <thead><tr><th>Mineral</th><th class="text-end">Qty Sold</th><th class="text-end">Revenue</th></tr></thead>
          <tbody>
            @forelse ($topMinerals as $row)
              <tr><td>{{ $row->name }}</td><td class="text-end">{{ $row->qty }}</td><td class="text-end">${{ number_format($row->revenue, 2) }}</td></tr>
            @empty
              <tr><td colspan="3" class="text-center text-muted py-3">No data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header">Top Customers</div>
        <table class="table mb-0">
          <thead><tr><th>Customer</th><th class="text-end">Total Spent</th></tr></thead>
          <tbody>
            @foreach ($topCustomers as $c)
              <tr><td>{{ $c->name }}</td><td class="text-end">${{ number_format($c->total_spent ?? 0, 2) }}</td></tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection