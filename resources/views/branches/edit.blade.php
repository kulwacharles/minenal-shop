@extends('layouts.app')
@section('title', 'New Branch')
@section('page-title', 'New Branch')
@section('content')
  <form method="PUT" action="{{ route('branches.update', $branch->id) }}" class="card p-4">
    @csrf
    @method('PUT')
    @include('branches._form')
    @if ($errors->any())
      <div class="alert alert-danger mt-3 mb-0">
        @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
      </div>
    @endif
    <div class="mt-4">
      <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i> Update Branch</button>
      <a href="{{ route('branches.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
@endsection