@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Categories')
@section('page-actions')
  <a href="{{ route('categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> New Category</a>
@endsection
@section('content')
  <div class="card">
    <table class="table mb-0 align-middle">
      <thead><tr><th>Name</th><th>Slug</th><th>Minerals</th><th>Color</th><th></th></tr></thead>
      <tbody>
        @foreach ($categories as $c)
          <tr>
            <td class="fw-semibold">{{ $c->name }}</td>
            <td class="small text-muted">{{ $c->slug }}</td>
            <td>{{ $c->minerals_count }}</td>
            <td><span class="badge" style="background:{{ $c->color }}">&nbsp;&nbsp;&nbsp;</span></td>
            <td class="text-end">
              <a href="{{ route('categories.edit', $c) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
              <form method="POST" action="{{ route('categories.destroy', $c) }}" class="d-inline" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-3">{{ $categories->links() }}</div>
@endsection