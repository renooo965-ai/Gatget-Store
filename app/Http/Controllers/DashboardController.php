<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SalesTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalStock = (int) Product::sum('stock');
        $lowStockProducts = Product::where('stock', '<=', 5)->count();
        $inventoryValue = (float) Product::selectRaw(
            'COALESCE(SUM(price * stock), 0) as total'
        )->value('total');
        $categoryStats = Category::query()
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->select(
                'categories.name',
                DB::raw('COUNT(products.id) as product_count'),
                DB::raw('COALESCE(SUM(products.stock),0) as stock_total')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('categories.name')->get();
        $latestProducts = Product::with('category')->latest()->take(5)->get();

        $trendLabels = [];
        $salesTrend = [];
        $purchaseTrend = [];

        for ($i = 6; $i >= 0; $i--) {
            $trendLabels[] = Carbon::today()->subDays($i)->format('d M');
        }

        $trendStart = Carbon::today()->subDays(6)->startOfDay();
        $salesByDate = SalesTransaction::query()
            ->where('status', 'completed')
            ->where('transaction_date', '>=', $trendStart)
            ->get(['transaction_date', 'total_price'])
            ->groupBy(fn ($sale) => $sale->transaction_date->format('Y-m-d'));
        $purchasesByDate = Purchase::query()
            ->where('purchased_at', '>=', $trendStart)
            ->get(['purchased_at', 'total_price'])
            ->groupBy(fn ($purchase) => $purchase->purchased_at->format('Y-m-d'));

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $key = $date->format('Y-m-d');
            $salesTrend[] = (float) ($salesByDate->get($key)?->sum('total_price') ?? 0);
            $purchaseTrend[] = (float) ($purchasesByDate->get($key)?->sum('total_price') ?? 0);
        }

        return view('dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalStock',
            'lowStockProducts',
            'inventoryValue',
            'categoryStats',
            'latestProducts',
            'trendLabels',
            'salesTrend',
            'purchaseTrend'
        ));
    }
}
