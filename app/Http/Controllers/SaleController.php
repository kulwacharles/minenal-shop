<?php
namespace App\Http\Controllers;

use App\Http\Requests\SaleRequest;
use App\Models\{Sale, Mineral, Customer};
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function __construct(private StockService $stock) {}

    public function index(Request $request)
    {
        $branchId = current_branch_id();
        $isSuperAdmin = auth()->user()->isSuperAdmin();

        $sales = Sale::with('customer', 'user', 'branch')
            // Non-super-admin sees only their branch
            ->when(!$isSuperAdmin, fn($q) => $q->where('branch_id', auth()->user()->branch_id))
            ->when($isSuperAdmin && $branchId, fn($q) => $q->where('branch_id', $branchId))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->when($request->from,  fn($q, $d) => $q->whereDate('sold_at', '>=', $d))
            ->when($request->to,    fn($q, $d) => $q->whereDate('sold_at', '<=', $d))
            ->latest('sold_at')
            ->paginate(15)->withQueryString();

        return view('sales.index', compact('sales'));
    }
    public function create()
{
    $this->authorize('create', \App\Models\Sale::class);
    $branchId = current_branch_id();

    return view('sales.create', [
        'customers' => \App\Models\Customer::orderBy('name')->get(),
        'minerals'  => Mineral::active()->orderBy('name')->get(),
        'branchId'  => $branchId,
    ]);
}
    public function store(SaleRequest $request)
    {
        $this->authorize('create', \App\Models\Sale::class);
        $data     = $request->validated();
        $branchId = current_branch_id();

        if (!$branchId) {
            return back()->withInput()->with('error', 'No branch context. Select a branch first.');
        }

        try {
            $sale = DB::transaction(function () use ($data, $branchId) {
                $subtotal = collect($data['items'])->sum(fn($i) => $i['unit_price'] * $i['quantity']);
                $discount = (float)($data['discount'] ?? 0);
                $tax      = (float)($data['tax'] ?? 0);

                $sale = Sale::create([
                    'invoice_no'     => 'INV-' . strtoupper(Str::random(8)),
                    'branch_id'      => $branchId,
                    'customer_id'    => $data['customer_id'] ?? null,
                    'user_id'        => auth()->id(),
                    'subtotal'       => $subtotal,
                    'discount'       => $discount,
                    'tax'            => $tax,
                    'total'          => $subtotal - $discount + $tax,
                    'payment_method' => $data['payment_method'],
                    'status'         => 'paid',
                    'notes'          => $data['notes'] ?? null,
                    'sold_at'        => now(),
                ]);

                foreach ($data['items'] as $row) {
                    $mineral = Mineral::findOrFail($row['mineral_id']);
                    $sale->items()->create([
                        'mineral_id' => $mineral->id,
                        'quantity'   => $row['quantity'],
                        'unit_price' => $row['unit_price'],
                        'subtotal'   => $row['unit_price'] * $row['quantity'],
                    ]);
                    $this->stock->decrease($mineral, $row['quantity'], $branchId, "Sale {$sale->invoice_no}", $sale);
                }

                return $sale;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('sales.show', $sale)->with('success', 'Sale recorded.');
    }

    public function show(Sale $sale)
    {
        $this->authorize('view', $sale);
        $sale->load('items.mineral', 'customer', 'user', 'branch');
        return view('sales.show', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        $this->authorize('refund', $sale);

        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $this->stock->increase($item->mineral, $item->quantity, $sale->branch_id, 'return', "Refund {$sale->invoice_no}", $sale);
            }
            $sale->update(['status' => 'refunded']);
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Sale refunded & stock restored.');
    }
    
}