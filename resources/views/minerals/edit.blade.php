@extends('layouts.app')
@section('title', 'Edit Mineral')
@section('page-title', 'Edit Mineral')

@section('content')
  <form method="POST" action="{{ route('minerals.update', $mineral) }}" enctype="multipart/form-data" class="card p-4">
    @csrf @method('PUT')
    @include('minerals._form')
    @if ($errors->any())
      <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
    @endif
    <div class="mt-3">
      <button class="btn btn-primary">Update</button>
      <a href="{{ route('minerals.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
@endsection