<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create() {
        $products = Product::orderBy('name')->get();
        return view('pos.index', compact('products'));
    }

    public function store(Request $request) {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric',
            'items.*.discount' => 'nullable|numeric',
            'cash_received' => 'required|numeric',
            'payment_method' => 'required|in:cash,qris,transfer',
        ]);

        DB::beginTransaction();
        try {
            $total = collect($request->items)->sum(function ($item) {
                return ($item['price'] - ($item['discount'] ?? 0)) * $item['quantity'];
            });

            $sale = Sale::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'cash_received' => $request->cash_received,
                'change' => $request->cash_received - $total,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return redirect()->route('pos.create')->with('success', 'Sale completed.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Something went wrong.']);
        }
    }
}
