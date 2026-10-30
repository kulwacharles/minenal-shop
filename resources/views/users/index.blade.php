@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'Users')

@section('page-actions')
  @can('create', App\Models\User::class)
    <a href="{{ route('users.create') }}" class="btn btn-primary">
      <i class="bi bi-person-plus me-1"></i> New User
    </a>
  @endcan
@endsection

@section('content')
  <form method="GET" class="mb-3">
    <div class="input-group" style="max-width:360px">
      <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name or email…">
      <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
    </div>
  </form>

  <div class="card">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Branch</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse ($users as $u)
            <tr>
              <td class="fw-semibold">{{ $u->name }}</td>
              <td class="small">{{ $u->email }}</td>
              <td>
                @foreach ($u->roles as $r)
                  <span class="badge badge-soft-info">{{ $r->name }}</span>
                @endforeach
              </td>
              <td>
                @if ($u->branch)
                  {{ $u->branch->name }}
                @else
                  <span class="text-muted small">All branches</span>
                @endif
              </td>
              <td>
                @if ($u->is_active)
                  <span class="badge badge-soft-success">Active</span>
                @else
                  <span class="badge badge-soft-danger">Inactive</span>
                @endif
              </td>
              <td class="text-end">
                @can('update', $u)
                  <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                @endcan
                @can('delete', $u)
                  <form method="POST" action="{{ route('users.destroy', $u) }}" class="d-inline"
                        onsubmit="return confirm('Delete {{ addslashes($u->name) }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                @endcan
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center py-4" style="color:var(--app-muted)">No users found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="mt-3">{{ $users->links() }}</div>
@endsection