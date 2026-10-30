@extends('layouts.app')
@section('title', 'Edit Supplier')
@section('page-title', 'Edit Supplier')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('suppliers.index') }}">Suppliers</a></li>
      <li class="breadcrumb-item active">Edit</li>
    </ol>
  </nav>
@endsection

@section('content')
  <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="card p-4">
    @csrf @method('PUT')
    @include('suppliers._form')

    @if ($errors->any())
      <div class="alert alert-danger mt-3 mb-0">{{ $errors->first() }}</div>
    @endif

    <div class="mt-4">
      <button class="btn btn-primary"><i class="bi bi-check2 me-1"></i> Update</button>
      <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
  </form>
@endsection