@extends('layouts.app')
@section('title', 'Suppliers')
@section('page-title', 'Suppliers')

@section('page-actions')
  <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-lg me-1"></i> New Supplier
  </a>
@endsection

@section('content')
  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Supplier</th>
            <th>Contact Person</th>
            <th>Email / Phone</th>
            <th class="text-end">Minerals</th>
            <th class="text-end">Purchases</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($suppliers as $s)
            <tr>
              <td class="fw-semibold">{{ $s->name }}</td>
              <td class="small">{{ $s->contact_name ?? '—' }}</td>
              <td class="small" style="color:var(--app-muted)">
                @if ($s->email)<div><i class="bi bi-envelope me-1"></i>{{ $s->email }}</div>@endif
                @if ($s->phone)<div><i class="bi bi-phone me-1"></i>{{ $s->phone }}</div>@endif
                @if (!$s->email && !$s->phone) — @endif
              </td>
              <td class="text-end"><span class="badge badge-soft-info">{{ $s->minerals_count }}</span></td>
              <td class="text-end"><span class="badge badge-soft-warning">{{ $s->purchases_count }}</span></td>
              <td class="text-end">
                <a href="{{ route('suppliers.edit', $s) }}" class="btn btn-sm btn-outline-secondary">
                  <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('suppliers.destroy', $s) }}" class="d-inline"
                      onsubmit="return confirm('Delete {{ addslashes($s->name) }}?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4" style="color:var(--app-muted)">
                No suppliers yet. <a href="{{ route('suppliers.create') }}">Add one</a>.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $suppliers->links() }}</div>
@endsection