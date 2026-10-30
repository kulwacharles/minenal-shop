@extends('layouts.app')
@section('title', 'Purchases')
@section('page-title', 'Purchases')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active">Purchases</li>
    </ol>
  </nav>
@endsection

@section('page-actions')
  <a href="{{ route('purchases.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> New Purchase
  </a>
@endsection

@section('content')
  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Reference</th>
            <th>Supplier</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($purchases as $p)
            <tr>
              <td>
                <a href="{{ route('purchases.show', $p) }}"
                   class="fw-semibold text-decoration-none" style="color:var(--accent)">
                  {{ $p->reference_no }}
                </a>
              </td>
              <td>{{ $p->supplier->name ?? '—' }}</td>
              <td>{{ $p->items->count() }}</td>
              <td class="fw-semibold">${{ number_format($p->total, 2) }}</td>
              <td>
                @php
                  $map = [
                    'pending'   => 'warning',
                    'received'  => 'success',
                    'cancelled' => 'danger',
                  ];
                  $cls = $map[$p->status] ?? 'info';
                @endphp
                <span class="badge badge-soft-{{ $cls }}">{{ ucfirst($p->status) }}</span>
              </td>
              <td class="small" style="color:var(--app-muted)">
                {{ $p->purchased_at->format('M d, Y H:i') }}
              </td>
              <td class="text-end">
                @if ($p->status === 'pending')
                  <form method="POST" action="{{ route('purchases.receive', $p) }}"
                        class="d-inline"
                        onsubmit="return confirm('Receive this purchase? Stock will be added.')">
                    @csrf
                    <button class="btn btn-sm btn-outline-success" title="Receive">
                      <i class="bi bi-box-arrow-in-down"></i>
                    </button>
                  </form>
                @endif
                @if ($p->status !== 'received')
                  <form method="POST" action="{{ route('purchases.destroy', $p) }}"
                        class="d-inline"
                        onsubmit="return confirm('Delete this purchase?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Delete">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="text-center py-4" style="color:var(--app-muted)">
                No purchases yet.
                <a href="{{ route('purchases.create') }}">Create your first purchase</a>.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $purchases->links() }}</div>
@endsection