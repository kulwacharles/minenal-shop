<?php
namespace App\Http\Controllers;

use App\Models\Mineral;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function __construct(private StockService $stock) {}


    public function history(Mineral $mineral)
    {
        $movements = $mineral->stockMovements()->with('user')->latest()->paginate(20);
        return view('stock.history', compact('mineral', 'movements'));
    }
    public function index(Request $request)
    {
        $this->authorize('viewAny', \App\Models\StockMovement::class);

        $branchId     = current_branch_id();
        $isSuperAdmin = auth()->user()->isSuperAdmin();
        $filter       = $request->filter ?? 'all';

        $minerals = Mineral::with(['category', 'branchStocks'])
            ->when(!$isSuperAdmin, fn($q) => $q->whereHas('branchStocks', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            }))
            ->when($filter === 'low', fn($q) => $q->whereColumn('stock_qty', '<=', 'min_stock_qty'))
            ->when($filter === 'out', fn($q) => $q->where('stock_qty', 0))
            ->search($request->q)
            ->orderBy('stock_qty')
            ->paginate(20)->withQueryString();

        return view('stock.index', compact('minerals', 'filter', 'branchId'));
    }

    public function adjust(Request $request, Mineral $mineral)
    {
        $this->authorize('adjust', \App\Models\StockMovement::class);

        $data = $request->validate([
            'delta' => 'required|integer|not_in:0',
            'notes' => 'nullable|string',
        ]);
        $branchId = current_branch_id();

        try {
            $this->stock->adjust($mineral, $data['delta'], $branchId, $data['notes'] ?? null);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Stock adjusted for {$mineral->name}.");
    }
}