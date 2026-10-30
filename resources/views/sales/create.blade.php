@extends('layouts.app')
@section('title', 'New Sale')
@section('page-title', 'New Sale / POS')

@section('content')
<form method="POST" action="{{ route('sales.store') }}" id="posForm">
  @csrf
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card p-3">
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <label class="form-label">Customer</label>
            <select name="customer_id" class="form-select">
              <option value="">Walk-in</option>
              @foreach ($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" required>
              <option value="cash">Cash</option>
              <option value="card">Card</option>
              <option value="transfer">Transfer</option>
            </select>
          </div>
        </div>

        <table class="table align-middle" id="itemsTable">
          <thead>
            <tr><th>Mineral</th><th style="width:110px">Qty</th><th style="width:130px">Unit Price</th><th class="text-end">Subtotal</th><th></th></tr>
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
        <div class="d-flex justify-content-between mb-2"><span>Subtotal</span><span id="subtotal">$0.00</span></div>
        <div class="mb-2">
          <label class="form-label small">Discount</label>
          <input type="number" name="discount" id="discount" value="0" step="0.01" min="0" class="form-control form-control-sm">
        </div>
        <div class="mb-2">
          <label class="form-label small">Tax</label>
          <input type="number" name="tax" id="tax" value="0" step="0.01" min="0" class="form-control form-control-sm">
        </div>
        <hr>
        <div class="d-flex justify-content-between fw-bold fs-5 mb-3"><span>Total</span><span id="total">$0.00</span></div>
        <div class="mb-2">
          <label class="form-label small">Notes</label>
          <textarea name="notes" rows="2" class="form-control form-control-sm"></textarea>
        </div>
        <button class="btn btn-primary w-100"><i class="bi bi-check2-circle me-1"></i> Complete Sale</button>
      </div>
    </div>
  </div>
  @if ($errors->any())<div class="alert alert-danger mt-3">{{ $errors->first() }}</div>@endif
</form>

<template id="rowTemplate">
  <tr>
    <td>
      <select name="items[__i__][mineral_id]" class="form-select form-select-sm mineral-select" required>
        <option value="">Select mineral…</option>
        @foreach ($minerals as $m)
          <option value="{{ $m->id }}" data-price="{{ $m->sale_price }}" data-stock="{{ $m->stock_qty }}">
            {{ $m->name }} ({{ $m->stock_qty }} {{ $m->unit }})
          </option>
        @endforeach
      </select>
    </td>
    <td><input type="number" name="items[__i__][quantity]" class="form-control form-control-sm qty" value="1" min="1" required></td>
    <td><input type="number" name="items[__i__][unit_price]" class="form-control form-control-sm price" step="0.01" min="0" required></td>
    <td class="text-end line-subtotal">$0.00</td>
    <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-x"></i></button></td>
  </tr>
</template>
@endsection

@push('scripts')
<script>
(function () {
  const tbody = document.querySelector('#itemsTable tbody');
  const tpl = document.getElementById('rowTemplate').innerHTML;
  let index = 0;

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
      const sel = row.querySelector('.mineral-select');
      const price = row.querySelector('.price');
      const qty = row.querySelector('.qty');
      sel.addEventListener('change', () => {
        const opt = sel.selectedOptions[0];
        price.value = opt?.dataset.price ?? '';
        recalc();
      });
      qty.addEventListener('input', recalc);
      price.addEventListener('input', recalc);
      row.querySelector('.remove-row').addEventListener('click', () => { row.remove(); recalc(); });
    });
  }

  function recalc() {
    let subtotal = 0;
    tbody.querySelectorAll('tr').forEach(row => {
      const qty = parseFloat(row.querySelector('.qty').value) || 0;
      const price = parseFloat(row.querySelector('.price').value) || 0;
      const line = qty * price;
      row.querySelector('.line-subtotal').textContent = '$' + line.toFixed(2);
      subtotal += line;
    });
    const disc = parseFloat(document.getElementById('discount').value) || 0;
    const tax = parseFloat(document.getElementById('tax').value) || 0;
    const total = subtotal - disc + tax;
    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('total').textContent = '$' + total.toFixed(2);
  }

  document.getElementById('addRow').addEventListener('click', addRow);
  document.getElementById('discount').addEventListener('input', recalc);
  document.getElementById('tax').addEventListener('input', recalc);

  addRow(); // start with one line
})();
</script>
@endpush