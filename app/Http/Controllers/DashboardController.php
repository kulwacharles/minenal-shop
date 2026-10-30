<?php

namespace App\Http\Controllers;

use App\Models\Mineral;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function index()
    {
        $branchId     = current_branch_id();
        $isSuperAdmin = auth()->user()->isSuperAdmin();

        $mineralQuery = Mineral::query();
        $saleQuery    = Sale::query();

        if (!$isSuperAdmin) {
            $bid = auth()->user()->branch_id;
            $saleQuery->where('branch_id', $bid);
        } elseif ($branchId) {
            $saleQuery->where('branch_id', $branchId);
        }

        $stats = [
            'minerals'    => $mineralQuery->count(),
            'stock_value' => $mineralQuery->sum(DB::raw('stock_qty * cost_price')),
            'today_sales' => (clone $saleQuery)->whereDate('sold_at', today())
                                                ->where('status', 'paid')->sum('total'),
            'low_stock'   => $mineralQuery->lowStock()->count(),
        ];

        $last7 = collect(range(6, 0))->map(fn($d) => today()->subDays($d));

        $salesByDay = (clone $saleQuery)->where('status', 'paid')
            ->where('sold_at', '>=', today()->subDays(6))
            ->selectRaw('DATE(sold_at) as d, SUM(total) as t')
            ->groupBy('d')->pluck('t', 'd');

        $chart = [
            'labels' => $last7->map(fn($d) => $d->format('D'))->all(),
            'data'   => $last7->map(fn($d) => (float)($salesByDay[$d->toDateString()] ?? 0))->all(),
        ];

        $lowStock = $mineralQuery->lowStock()->active()->orderBy('stock_qty')->take(5)->get();
        $recentSales = (clone $saleQuery)->with('customer')->latest('sold_at')->take(6)->get();

        return view('dashboard', compact('stats', 'chart', 'lowStock', 'recentSales'));
    }
}