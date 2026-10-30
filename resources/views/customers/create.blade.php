@extends('layouts.app')
@section('title', 'New Customer')
@section('page-title', 'New Customer')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
      <li class="breadcrumb-item active">New</li>
    </ol>
  </nav>
@endsection

@section('content')
  <form method="POST" action="{{ route('customers.store') }}" class="card p-4">
    @csrf
    @include('customers._form')

    @if ($errors->any())
      <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
    @endif

    <div class="mt-4">
      <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i> Save</button>
      <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
@endsection