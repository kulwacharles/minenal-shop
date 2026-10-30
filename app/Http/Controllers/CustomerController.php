<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // public function index()
    // {
    //     $customers = Customer::withCount('sales')->orderBy('name')->paginate(15);
    //     return view('customers.index', compact('customers'));
    // }
    public function index(Request $request)
    {
        $customers = Customer::withCount('sales')
            ->when($request->q, fn($q, $term) => $q->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
            }))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function create() { return view('customers.create'); }

    public function store(Request $request)
    {
        Customer::create($this->validateData($request));
        return redirect()->route('customers.index')->with('success', 'Customer created.');
    }

    public function edit(Customer $customer) { return view('customers.edit', compact('customer')); }

    public function update(Request $request, Customer $customer)
    {
        $customer->update($this->validateData($request));
        return redirect()->route('customers.index')->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer deleted.');
    }

    protected function validateData(Request $r): array
    {
        return $r->validate([
            'name'    => 'required|string|max:150',
            'email'   => 'nullable|email|max:150',
            'phone'   => 'nullable|string|max:40',
            'address' => 'nullable|string',
            'notes'   => 'nullable|string',
        ]);
    }
}