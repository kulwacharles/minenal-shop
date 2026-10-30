@extends('layouts.app')
@section('title', 'New Purchase')
@section('page-title', 'New Purchase')

@section('breadcrumb')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('purchases.index') }}">Purchases</a></li>
      <li class="breadcrumb-item active">New</li>
    </ol>
  </nav>
@endsection

@section('content')
<form method="POST" action="{{ route('purchases.store') }}" id="purchaseForm">
  @csrf

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-3">
        <div class="mb-3">
          <label class="form-label">Supplier <span class="text-danger">*</span></label>
          <select name="supplier_id" class="form-select" required>
            <option value="">Select supplier…</option>
            @foreach ($suppliers as $s)
              <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
            @endforeach
          </select>
        </div>

        <table class="table align-middle" id="itemsTable">
          <thead>
            <tr>
              <th>Mineral</th>
              <th style="width:110px">Qty</th>
              <th style="width:130px">Unit Cost</th>
              <th class="text-end">Subtotal</th>
              <th style="width:40px"></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

        <button type="button" id="addRow" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-plus-lg"></i> Add Item
        </button>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card p-3">
        <h6 class="mb-3">Summary</h6>
        <div class="d-flex justify-content-between mb-2">
          <span>Total</span>
          <span class="fw-bold fs-5" id="total">$0.00</span>
        </div>

        <div class="mb-3">
          <label class="form-label small">Notes</label>
          <textarea name="notes" rows="3" class="form-control form-control-sm">{{ old('notes') }}</textarea>
        </div>

        <div class="alert alert-info py-2 px-3 small mb-3">
          <i class="bi bi-info-circle me-1"></i>
          Purchase starts as <strong>pending</strong>. Click <em>Receive</em> on the
          purchase page to add items to stock.
        </div>

        <button class="btn btn-primary w-100">
          <i class="bi bi-check2-circle me-1"></i> Create Purchase
        </button>
      </div>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger mt-3">
      @foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
  @endif
</form>

<template id="rowTemplate">
  <tr>
    <td>
      <select name="items[__i__][mineral_id]" class="form-select form-select-sm mineral-select" required>
        <option value="">Select mineral…</option>
        @foreach ($minerals as $m)
          <option value="{{ $m->id }}" data-cost="{{ $m->cost_price }}">
            {{ $m->name }} ({{ $m->stock_qty }} {{ $m->unit }} in stock)
          </option>
        @endforeach
      </select>
    </td>
    <td>
      <input type="number" name="items[__i__][quantity]" class="form-control form-control-sm qty"
             value="1" min="1" required>
    </td>
    <td>
      <input type="number" name="items[__i__][unit_cost]" class="form-control form-control-sm cost"
             step="0.01" min="0" required>
    </td>
    <td class="text-end line-subtotal">$0.00</td>
    <td>
      <button type="button" class="btn btn-sm btn-outline-danger remove-row">
        <i class="bi bi-x"></i>
      </button>
    </td>
  </tr>
</template>
@endsection

@push('scripts')
<script>
(function () {
  const tbody = document.querySelector('#itemsTable tbody');
  const tpl   = document.getElementById('rowTemplate').innerHTML;
  let index   = 0;

  function addRow() {
    const html = tpl.replaceAll('__i__', index++);
    const wrap = document.createElement('tbody');
    wrap.innerHTML = html.trim();
    tbody.appendChild(wrap.firstElementChild);
    bind();
    recalc();
  }

  function bind() {
    tbody.querySelectorAll('tr').forEach(row => {
      if (row.dataset.bound) return;
      row.dataset.bound = '1';

      const sel  = row.querySelector('.mineral-select');
      const cost = row.querySelector('.cost');
      const qty  = row.querySelector('.qty');

      sel.addEventListener('change', () => {
        const opt = sel.selectedOptions[0];
        if (opt?.dataset.cost) cost.value = opt.dataset.cost;
        recalc();
      });

      qty.addEventListener('input',  recalc);
      cost.addEventListener('input', recalc);

      row.querySelector('.remove-row').addEventListener('click', () => {
        row.remove();
        recalc();
      });
    });
  }

  function recalc() {
    let total = 0;
    tbody.querySelectorAll('tr').forEach(row => {
      const qty  = parseFloat(row.querySelector('.qty').value)  || 0;
      const cost = parseFloat(row.querySelector('.cost').value) || 0;
      const line = qty * cost;
      row.querySelector('.line-subtotal').textContent = '$' + line.toFixed(2);
      total += line;
    });
    document.getElementById('total').textContent = '$' + total.toFixed(2);
  }

  document.getElementById('addRow').addEventListener('click', addRow);
  addRow();
})();
</script>
@endpush