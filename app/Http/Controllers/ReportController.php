<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'period' => 'nullable|in:today,yesterday,week,month,year',
        ]);

        $query = Transaction::completed();

        // Apply date filters
        if ($request->period) {
            switch ($request->period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', yesterday());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        } elseif ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }

        // Get summary data
        $summary = $query->selectRaw('
            COUNT(*) as total_transactions,
            SUM(total_amount) as total_sales,
            SUM(subtotal) as total_subtotal,
            SUM(discount_amount) as total_discount,
            AVG(total_amount) as average_transaction,
            SUM((SELECT SUM(quantity) FROM transaction_items WHERE transaction_id = transactions.id)) as total_items
        ')->first();

        // Get daily sales data for chart
        $dailySales = $query->selectRaw('
            DATE(created_at) as date,
            COUNT(*) as transactions,
            SUM(total_amount) as sales,
            SUM((SELECT SUM(quantity) FROM transaction_items WHERE transaction_id = transactions.id)) as items
        ')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        // Get payment method breakdown
        $paymentMethods = $query->selectRaw('
            payment_method,
            COUNT(*) as count,
            SUM(total_amount) as total
        ')
        ->groupBy('payment_method')
        ->get();

        // Get top selling products
        $topProducts = TransactionItem::select('product_name')
            ->selectRaw('
                SUM(quantity) as total_sold,
                SUM(subtotal) as total_revenue,
                COUNT(DISTINCT transaction_id) as transaction_count
            ')
            ->whereHas('transaction', function ($q) use ($query) {
                $q->completed();
                // Apply same date filters as main query
                if (request('period')) {
                    switch (request('period')) {
                        case 'today':
                            $q->whereDate('created_at', today());
                            break;
                        case 'yesterday':
                            $q->whereDate('created_at', yesterday());
                            break;
                        case 'week':
                            $q->whereBetween('created_at', [
                                now()->startOfWeek(),
                                now()->endOfWeek()
                            ]);
                            break;
                        case 'month':
                            $q->whereMonth('created_at', now()->month)
                              ->whereYear('created_at', now()->year);
                            break;
                        case 'year':
                            $q->whereYear('created_at', now()->year);
                            break;
                    }
                } elseif (request('start_date') && request('end_date')) {
                    $q->whereBetween('created_at', [
                        request('start_date') . ' 00:00:00',
                        request('end_date') . ' 23:59:59'
                    ]);
                }
            })
            ->groupBy('product_name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $summary,
                'daily_sales' => $dailySales,
                'payment_methods' => $paymentMethods,
                'top_products' => $topProducts,
            ]
        ]);
    }

    public function inventoryReport()
    {
        // Stock summary
        $stockSummary = Product::selectRaw('
            COUNT(*) as total_products,
            SUM(stock * buy_price) as total_stock_value,
            SUM(CASE WHEN stock <= min_stock THEN 1 ELSE 0 END) as low_stock_count,
            SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as out_of_stock_count
        ')->first();

        // Products by category
        $categoryBreakdown = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as category_name')
            ->selectRaw('
                COUNT(products.id) as product_count,
                SUM(products.stock) as total_stock,
                SUM(products.stock * products.buy_price) as stock_value
            ')
            ->where('products.is_active', true)
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Low stock products
        $lowStockProducts = Product::with('category')
            ->lowStock()
            ->active()
            ->orderBy('stock')
            ->limit(20)
            ->get();

        // Top value products
        $topValueProducts = Product::with('category')
            ->selectRaw('*, (stock * buy_price) as stock_value')
            ->active()
            ->orderBy('stock_value', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $stockSummary,
                'category_breakdown' => $categoryBreakdown,
                'low_stock_products' => $lowStockProducts,
                'top_value_products' => $topValueProducts,
            ]
        ]);
    }

    public function cashierReport(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $query = Transaction::with('user')->completed();

        // Apply date filters
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        } else {
            // Default to current month
            $query->whereMonth('created_at', now()->month)
                  ->whereYear('created_at', now()->year);
        }

        // Filter by specific cashier
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Get cashier performance
        $cashierPerformance = $query->select('user_id')
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(total_amount) as total_sales,
                AVG(total_amount) as average_transaction,
                SUM((SELECT SUM(quantity) FROM transaction_items WHERE transaction_id = transactions.id)) as total_items
            ')
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderBy('total_sales', 'desc')
            ->get();

        // Get daily performance for selected cashier or all
        $dailyPerformance = $query->selectRaw('
            DATE(created_at) as date,
            user_id,
            COUNT(*) as transactions,
            SUM(total_amount) as sales
        ')
        ->with('user:id,name')
        ->groupBy('date', 'user_id')
        ->orderBy('date')
        ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'cashier_performance' => $cashierPerformance,
                'daily_performance' => $dailyPerformance,
            ]
        ]);
    }

    public function dashboardStats()
    {
        // Today's stats
        $todayStats = Transaction::completed()
            ->whereDate('created_at', today())
            ->selectRaw('
                COUNT(*) as transactions,
                SUM(total_amount) as sales,
                SUM((SELECT SUM(quantity) FROM transaction_items WHERE transaction_id = transactions.id)) as items_sold
            ')
            ->first();

        // Yesterday's stats for comparison
        $yesterdayStats = Transaction::completed()
            ->whereDate('created_at', yesterday())
            ->selectRaw('
                COUNT(*) as transactions,
                SUM(total_amount) as sales,
                SUM((SELECT SUM(quantity) FROM transaction_items WHERE transaction_id = transactions.id)) as items_sold
            ')
            ->first();

        // Stock stats
        $stockStats = Product::selectRaw('
            COUNT(*) as total_products,
            SUM(stock) as total_stock,
            SUM(CASE WHEN stock <= min_stock THEN 1 ELSE 0 END) as low_stock_count
        ')->first();

        // Recent transactions
        $recentTransactions = Transaction::with(['user:id,name', 'items'])
            ->completed()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Calculate percentage changes
        $salesChange = $yesterdayStats->sales > 0 
            ? (($todayStats->sales - $yesterdayStats->sales) / $yesterdayStats->sales) * 100 
            : 0;

        $transactionsChange = $yesterdayStats->transactions > 0 
            ? (($todayStats->transactions - $yesterdayStats->transactions) / $yesterdayStats->transactions) * 100 
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'today' => $todayStats,
                'yesterday' => $yesterdayStats,
                'stock' => $stockStats,
                'recent_transactions' => $recentTransactions,
                'changes' => [
                    'sales' => round($salesChange, 1),
                    'transactions' => round($transactionsChange, 1),
                ]
            ]
        ]);
    }
}