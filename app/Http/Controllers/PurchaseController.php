<?php
namespace App\Http\Controllers;

use App\Models\{Purchase, Mineral, Supplier};
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function __construct(private StockService $stock) {}

  

    public function create()
    {
        return view('purchases.create', [
            'suppliers' => Supplier::orderBy('name')->get(),
            'minerals'  => Mineral::orderBy('name')->get(),
        ]);
    }


    public function show(Purchase $purchase)
    {
        $purchase->load('items.mineral', 'supplier', 'user');
        return view('purchases.show', compact('purchase'));
    }

    

    public function destroy(Purchase $purchase)
    {
        if ($purchase->status === 'received') {
            return back()->with('error', 'Cannot delete a received purchase.');
        }
        $purchase->delete();
        return redirect()->route('purchases.index')->with('success', 'Purchase deleted.');
    }
    public function index()
{
    $isSuperAdmin = auth()->user()->isSuperAdmin();
    $purchases = Purchase::with('supplier', 'user', 'items', 'branch')
        ->when(!$isSuperAdmin, fn($q) => $q->where('branch_id', auth()->user()->branch_id))
        ->latest('purchased_at')
        ->paginate(15);
    return view('purchases.index', compact('purchases'));
}

public function store(Request $request)
{
    $this->authorize('create', \App\Models\Purchase::class);
    $data = $request->validate([
        'supplier_id'        => 'required|exists:suppliers,id',
        'notes'              => 'nullable|string',
        'items'              => 'required|array|min:1',
        'items.*.mineral_id' => 'required|exists:minerals,id',
        'items.*.quantity'   => 'required|integer|min:1',
        'items.*.unit_cost'  => 'required|numeric|min:0',
    ]);
    $branchId = current_branch_id();
    if (!$branchId) return back()->with('error', 'No branch context.');

    $purchase = DB::transaction(function () use ($data, $branchId) {
        $total = collect($data['items'])->sum(fn($i) => $i['unit_cost'] * $i['quantity']);
        $p = Purchase::create([
            'reference_no' => 'PO-' . strtoupper(Str::random(8)),
            'branch_id'    => $branchId,
            'supplier_id'  => $data['supplier_id'],
            'user_id'      => auth()->id(),
            'total'        => $total,
            'status'       => 'pending',
            'notes'        => $data['notes'] ?? null,
            'purchased_at' => now(),
        ]);
        foreach ($data['items'] as $row) {
            $p->items()->create([
                'mineral_id' => $row['mineral_id'],
                'quantity'   => $row['quantity'],
                'unit_cost'  => $row['unit_cost'],
                'subtotal'   => $row['unit_cost'] * $row['quantity'],
            ]);
        }
        return $p;
    });

    return redirect()->route('purchases.show', $purchase)->with('success', 'Purchase created.');
}

public function receive(Purchase $purchase)
{
    $this->authorize('receive', \App\Models\Purchase::class);
    if ($purchase->status !== 'pending') {
        return back()->with('error', 'Only pending purchases can be received.');
    }

    DB::transaction(function () use ($purchase) {
        foreach ($purchase->items as $item) {
            $this->stock->increase(
                $item->mineral,
                $item->quantity,
                $purchase->branch_id,
                'purchase',
                "Purchase {$purchase->reference_no}",
                $purchase
            );
            $item->mineral->update(['cost_price' => $item->unit_cost]);
        }
        $purchase->update(['status' => 'received']);
    });

    return back()->with('success', 'Purchase received and stock updated.');
}
}