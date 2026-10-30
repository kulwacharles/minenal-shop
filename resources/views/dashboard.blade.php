@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Dashboard</li>
    </ol>
  </nav>
@endsection

@section('content')
  <div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card stat-card h-100 p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon stat-icon-primary"><i class="bi bi-gem"></i></div>
          <div>
            <div class="stat-value">{{ number_format($stats['minerals']) }}</div>
            <div class="stat-label">Total Minerals</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card stat-card h-100 p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon stat-icon-success"><i class="bi bi-cash-stack"></i></div>
          <div>
            <div class="stat-value">${{ number_format($stats['stock_value'], 2) }}</div>
            <div class="stat-label">Stock Value</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card stat-card h-100 p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon stat-icon-info"><i class="bi bi-receipt"></i></div>
          <div>
            <div class="stat-value">${{ number_format($stats['today_sales'], 2) }}</div>
            <div class="stat-label">Today's Sales</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card stat-card h-100 p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="stat-icon stat-icon-danger"><i class="bi bi-exclamation-triangle"></i></div>
          <div>
            <div class="stat-value">{{ $stats['low_stock'] }}</div>
            <div class="stat-label">Low Stock Items</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-12 col-lg-8">
      <div class="card h-100">
        <div class="card-header">Sales Overview (Last 7 days)</div>
        <div class="card-body"><canvas id="salesChart" height="110"></canvas></div>
      </div>
    </div>
    <div class="col-12 col-lg-4">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <span>Low Stock</span>
          <a href="{{ route('stock.index', ['filter' => 'low']) }}" class="small text-decoration-none" style="color:var(--accent)">View all</a>
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            @forelse ($lowStock as $m)
              <li class="list-group-item px-3 py-3">
                <div class="d-flex justify-content-between small mb-1">
                  <a href="{{ route('minerals.show', $m) }}" class="fw-semibold text-decoration-none" style="color:var(--app-text)">
                    {{ $m->name }}
                  </a>
                  <span class="{{ $m->stock_qty === 0 ? 'text-danger' : 'text-warning' }}">{{ $m->stock_qty }} left</span>
                </div>
                <div class="progress" style="height:6px">
                  <div class="progress-bar {{ $m->stock_qty === 0 ? 'bg-danger' : 'bg-warning' }}"
                       style="width:{{ min(100, ($m->stock_qty / max(1, $m->min_stock_qty)) * 100) }}%"></div>
                </div>
              </li>
            @empty
              <li class="list-group-item text-center py-4 small" style="color:var(--app-muted)">All stock healthy ✓</li>
            @endforelse
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span>Recent Sales</span>
      <a href="{{ route('sales.index') }}" class="btn btn-sm btn-outline-secondary">View all</a>
    </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr><th>Invoice</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr>
        </thead>
        <tbody>
          @forelse ($recentSales as $s)
            <tr>
              <td><a href="{{ route('sales.show', $s) }}" class="fw-semibold text-decoration-none" style="color:var(--accent)">{{ $s->invoice_no }}</a></td>
              <td>{{ $s->customer->name ?? 'Walk-in' }}</td>
              <td class="fw-semibold">${{ number_format($s->total, 2) }}</td>
              <td>
                @php
                  $map = ['paid' => 'success', 'pending' => 'warning', 'refunded' => 'danger'];
                  $cls = $map[$s->status] ?? 'info';
                @endphp
                <span class="badge badge-soft-{{ $cls }}">{{ ucfirst($s->status) }}</span>
              </td>
              <td class="small" style="color:var(--app-muted)">{{ $s->sold_at->format('M d, H:i') }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center py-4 small" style="color:var(--app-muted)">No sales yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script>
    const chart = new Chart(document.getElementById('salesChart'), {
      type: 'line',
      data: {
        labels: @json($chart['labels']),
        datasets: [{
          data: @json($chart['data']),
          borderColor: '#d4a24c', backgroundColor: 'rgba(212,162,76,.12)',
          fill: true, tension: .35,
          pointBackgroundColor: '#d4a24c', pointRadius: 4,
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, grid: { color: MineralTheme.grid() }, ticks: { color: MineralTheme.tick() } },
          x: { grid: { display: false }, ticks: { color: MineralTheme.tick() } }
        }
      }
    });
    MineralTheme.register(chart);
  </script>
@endpush