<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SalesTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index()
    {
        $sales = SalesTransaction::with(['product', 'employee'])
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(10, ['*'], 'sales_page');
        $purchases = Purchase::with('product')
            ->latest('purchased_at')
            ->latest('id')
            ->paginate(10, ['*'], 'purchases_page');

        $salesTotal = SalesTransaction::where('status', 'completed')->sum('total_price');
        $purchasesTotal = Purchase::sum('total_price');

        return view('transactions.index', compact(
            'sales',
            'purchases',
            'salesTotal',
            'purchasesTotal'
        ));
    }

    public function createSale()
    {
        $products = Product::where('stock', '>', 0)->orderBy('name')->get();
        $employees = Employee::where('status', 'active')->orderBy('name')->get();

        return view('transactions.create-sale', compact('products', 'employees'));
    }

    public function storeSale(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => ['required', 'string', 'max:50', 'unique:sales_transactions,invoice_number'],
            'customer_name' => ['required', 'string', 'max:150'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', Rule::in(['cash', 'transfer', 'qris', 'card'])],
            'status' => ['required', Rule::in(['completed', 'pending', 'cancelled'])],
            'transaction_date' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::whereKey($validated['product_id'])->lockForUpdate()->firstOrFail();

            if ($validated['status'] !== 'cancelled' && $product->stock < $validated['quantity']) {
                abort(422, 'Stok produk tidak mencukupi.');
            }

            $unitPrice = (float) $product->price;
            $validated['unit_price'] = $unitPrice;
            $validated['total_price'] = $unitPrice * $validated['quantity'];

            SalesTransaction::create($validated);

            if ($validated['status'] === 'completed') {
                $product->decrement('stock', $validated['quantity']);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi penjualan berhasil disimpan.');
    }

    public function createPurchase()
    {
        $products = Product::orderBy('name')->get();

        return view('transactions.create-purchase', compact('products'));
    }

    public function storePurchase(Request $request)
    {
        $validated = $request->validate([
            'purchase_number' => ['required', 'string', 'max:50', 'unique:purchases,purchase_number'],
            'supplier_name' => ['required', 'string', 'max:150'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated) {
            $validated['total_price'] = (float) $validated['purchase_price'] * $validated['quantity'];
            Purchase::create($validated);
            Product::whereKey($validated['product_id'])->increment('stock', $validated['quantity']);
        });

        return redirect()->route('transactions.index')->with('success', 'Pembelian berhasil disimpan dan stok telah diperbarui.');
    }

    public function destroySale(SalesTransaction $sale)
    {
        DB::transaction(function () use ($sale) {
            if ($sale->status === 'completed') {
                $sale->product()->increment('stock', $sale->quantity);
            }
            $sale->delete();
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi penjualan berhasil dihapus.');
    }

    public function destroyPurchase(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            $product = Product::whereKey($purchase->product_id)->lockForUpdate()->firstOrFail();

            if ($product->stock < $purchase->quantity) {
                abort(422, 'Pembelian tidak dapat dihapus karena stok saat ini sudah digunakan atau dikurangi.');
            }

            $product->decrement('stock', $purchase->quantity);
            $purchase->delete();
        });

        return redirect()->route('transactions.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}
