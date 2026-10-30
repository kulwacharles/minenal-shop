@extends('layouts.app')
@section('title', 'New Category')
@section('page-title', 'New Category')
@section('content')
  <form method="POST" action="{{ route('categories.update', $category) }}" class="card p-4">
    @csrf @method('PUT')
    @include('categories._form')
    <div class="mt-3">
      <button class="btn btn-primary">Save</button>
      <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
@endsection