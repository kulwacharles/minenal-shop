@extends('layouts.app')
@section('title', 'Branches')
@section('page-title', 'Branches')

@section('page-actions')
  @can('create', App\Models\Branch::class)
    <a href="{{ route('branches.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-lg me-1"></i> New Branch
    </a>
  @endcan
@endsection

@section('content')
  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Code</th>
            <th>Contact</th>
            <th class="text-end">Users</th>
            <th class="text-end">Sales</th>
            <th class="text-end">Purchases</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($branches as $b)
            <tr>
              <td class="fw-semibold">{{ $b->name }}</td>
              <td><code>{{ $b->code }}</code></td>
              <td class="small" style="color:var(--app-muted)">
                @if ($b->phone)<div><i class="bi bi-phone me-1"></i>{{ $b->phone }}</div>@endif
                @if ($b->email)<div><i class="bi bi-envelope me-1"></i>{{ $b->email }}</div>@endif
                @if (!$b->phone && !$b->email) — @endif
              </td>
              <td class="text-end">{{ $b->users_count }}</td>
              <td class="text-end">{{ $b->sales_count }}</td>
              <td class="text-end">{{ $b->purchases_count }}</td>
              <td>
                @if ($b->is_active)
                  <span class="badge badge-soft-success">Active</span>
                @else
                  <span class="badge badge-soft-danger">Inactive</span>
                @endif
              </td>
              <td class="text-end">
                @can('update', $b)
                  <a href="{{ route('branches.edit', $b) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                @endcan
                @can('delete', $b)
                  <form method="POST" action="{{ route('branches.destroy', $b) }}" class="d-inline"
                        onsubmit="return confirm('Delete branch {{ addslashes($b->name) }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                @endcan
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center py-4" style="color:var(--app-muted)">No branches found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $branches->links() }}</div>
@endsection