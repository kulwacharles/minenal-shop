@extends('layouts.app')
@section('title', 'Customers')
@section('page-title', 'Customers')

@section('page-actions')
  <a href="{{ route('customers.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> New Customer
  </a>
@endsection

@section('content')
  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Contact</th>
            <th>Address</th>
            <th class="text-end">Sales</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($customers as $c)
            <tr>
              <td class="fw-semibold">{{ $c->name }}</td>
              <td class="small" style="color:var(--app-muted)">
                @if ($c->email)<div><i class="bi bi-envelope me-1"></i>{{ $c->email }}</div>@endif
                @if ($c->phone)<div><i class="bi bi-phone me-1"></i>{{ $c->phone }}</div>@endif
                @if (!$c->email && !$c->phone) — @endif
              </td>
              <td class="small" style="color:var(--app-muted)">
                {{ \Illuminate\Support\Str::limit($c->address, 60) ?: '—' }}
              </td>
              <td class="text-end"><span class="badge badge-soft-info">{{ $c->sales_count }}</span></td>
              <td class="text-end">
                <a href="{{ route('customers.edit', $c) }}" class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('customers.destroy', $c) }}" class="d-inline"
                      onsubmit="return confirm('Delete {{ addslashes($c->name) }}?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-4" style="color:var(--app-muted)">
                No customers yet. <a href="{{ route('customers.create') }}">Add one</a>.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $customers->links() }}</div>
@endsection