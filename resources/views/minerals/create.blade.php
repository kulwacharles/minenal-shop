@extends('layouts.app')
@section('title', 'New Mineral')
@section('page-title', 'New Mineral')

@section('content')
  <form method="POST" action="{{ route('minerals.store') }}" enctype="multipart/form-data" class="card p-4">
    @csrf
    @include('minerals._form')
    @if ($errors->any())
      <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
    @endif
    <div class="mt-3">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('minerals.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
@endsection