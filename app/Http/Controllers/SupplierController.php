<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('minerals', 'purchases')->orderBy('name')->paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create() { return view('suppliers.create'); }

    public function store(Request $request)
    {
        Supplier::create($this->validateData($request));
        return redirect()->route('suppliers.index')->with('success', 'Supplier created.');
    }

    public function edit(Supplier $supplier) { return view('suppliers.edit', compact('supplier')); }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update($this->validateData($request));
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted.');
    }

    protected function validateData(Request $r): array
    {
        return $r->validate([
            'name'         => 'required|string|max:150',
            'contact_name' => 'nullable|string|max:100',
            'email'        => 'nullable|email|max:150',
            'phone'        => 'nullable|string|max:40',
            'address'      => 'nullable|string',
            'notes'        => 'nullable|string',
        ]);
    }
}