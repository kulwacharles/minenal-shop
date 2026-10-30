<?php
namespace App\Http\Controllers;

use App\Models\{Sale, Purchase, Mineral, Customer};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->from ? \Carbon\Carbon::parse($request->from) : now()->startOfMonth();
        $to   = $request->to   ? \Carbon\Carbon::parse($request->to)   : now();

        $salesTotal   = Sale::whereBetween('sold_at', [$from->startOfDay(), $to->endOfDay()])
                            ->where('status', 'paid')->sum('total');
        $salesCount   = Sale::whereBetween('sold_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
                            ->where('status', 'paid')->count();
        $purchaseTotal= Purchase::whereBetween('purchased_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
                            ->where('status', 'received')->sum('total');

        $topMinerals = DB::table('sale_items')
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->join('minerals', 'minerals.id', '=', 'sale_items.mineral_id')
            ->where('sales.status', 'paid')
            ->whereBetween('sales.sold_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()])
            ->selectRaw('minerals.name, SUM(sale_items.quantity) as qty, SUM(sale_items.subtotal) as revenue')
            ->groupBy('minerals.id', 'minerals.name')
            ->orderByDesc('revenue')
            ->take(10)->get();

        $topCustomers = Customer::withSum(['sales as total_spent' => function ($q) use ($from, $to) {
                $q->where('status', 'paid')->whereBetween('sold_at', [$from->copy()->startOfDay(), $to->copy()->endOfDay()]);
            }], 'total')
            ->orderByDesc('total_spent')
            ->take(10)->get();

        $stockValue = Mineral::sum(DB::raw('stock_qty * cost_price'));

        return view('reports.index', compact(
            'from', 'to', 'salesTotal', 'salesCount', 'purchaseTotal',
            'topMinerals', 'topCustomers', 'stockValue'
        ));
    }
}