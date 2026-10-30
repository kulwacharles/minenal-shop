@extends('layouts.app')
@section('title', 'Stock')
@section('page-title', 'Stock Management')

@section('content')
  <div class="mb-3 btn-group">
    <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary @if($filter==='all') active @endif">All</a>
    <a href="{{ route('stock.index', ['filter'=>'low']) }}" class="btn btn-outline-secondary @if($filter==='low') active @endif">Low Stock</a>
    <a href="{{ route('stock.index', ['filter'=>'out']) }}" class="btn btn-outline-secondary @if($filter==='out') active @endif">Out of Stock</a>
  </div>

  <div class="card">
    <table class="table align-middle mb-0">
      <thead><tr><th>Mineral</th><th>Category</th><th>Stock</th><th>Min</th><th>Adjust</th></tr></thead>
      <tbody>
        @foreach ($minerals as $m)
          <tr>
            <td><a href="{{ route('minerals.show', $m) }}" class="fw-semibold text-decoration-none" style="color:var(--app-text)">{{ $m->name }}</a></td>
            <td class="small">{{ $m->category->name }}</td>
            <td><span class="{{ $m->is_low_stock ? 'text-danger fw-semibold' : '' }}">{{ $m->stock_qty }}</span></td>
            <td class="small text-muted">{{ $m->min_stock_qty }}</td>
            <td>
              <form method="POST" action="{{ route('stock.adjust', $m) }}" class="d-flex gap-1">
                @csrf
                <input type="number" name="delta" placeholder="+/-" class="form-control form-control-sm" style="width:80px" required>
                <input type="text" name="notes" placeholder="reason" class="form-control form-control-sm" style="width:140px">
                <button class="btn btn-sm btn-outline-primary">Adjust</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-3">{{ $minerals->links() }}</div>
@endsection