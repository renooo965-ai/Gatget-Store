@extends('layouts.app')
@section('title', 'Transaksi')
@section('content')
<div class="store-page-header">
    <div>
        <h1>Transaksi</h1>
        <p class="text-secondary small mb-0">Riwayat penjualan dan pembelian stok.</p>
    </div>
    @if(Auth::user()->role === 'admin')
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.create-purchase') }}" class="btn btn-outline-primary btn-sm"><i class="bi bi-box-arrow-in-down me-1"></i>Pembelian</a>
            <a href="{{ route('transactions.create-sale') }}" class="btn btn-primary btn-sm"><i class="bi bi-cart-plus me-1"></i>Penjualan</a>
        </div>
    @endif
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3"><div class="card store-metric h-100" style="--metric-color:#1cc88a"><div class="card-body">
        <div class="store-metric-label">Total Penjualan Selesai</div><div class="store-metric-value">Rp {{ number_format($salesTotal, 0, ',', '.') }}</div>
    </div></div></div>
    <div class="col-sm-6 col-lg-3"><div class="card store-metric h-100" style="--metric-color:#4e73df"><div class="card-body">
        <div class="store-metric-label">Total Pembelian</div><div class="store-metric-value">Rp {{ number_format($purchasesTotal, 0, ',', '.') }}</div>
    </div></div></div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h2 class="store-chart-title mb-3">Penjualan</h2>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>Invoice</th><th>Tanggal</th><th>Pelanggan</th><th>Pegawai</th><th>Produk</th><th>Jumlah</th><th>Total</th><th>Pembayaran</th><th>Status</th>@if(Auth::user()->role === 'admin')<th>Aksi</th>@endif</tr></thead>
                <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td class="fw-semibold">{{ $sale->invoice_number }}</td>
                        <td>{{ $sale->transaction_date->format('d/m/Y') }}</td>
                        <td>{{ $sale->customer_name }}</td>
                        <td>{{ $sale->employee?->name ?? '-' }}</td>
                        <td>{{ $sale->product?->name ?? '-' }}</td>
                        <td>{{ $sale->quantity }}</td>
                        <td>Rp {{ number_format((float) $sale->total_price, 0, ',', '.') }}</td>
                        <td>{{ strtoupper($sale->payment_method) }}</td>
                        <td><span class="badge {{ $sale->status === 'completed' ? 'text-bg-success' : ($sale->status === 'pending' ? 'text-bg-warning' : 'text-bg-secondary') }}">{{ ucfirst($sale->status) }}</span></td>
                        @if(Auth::user()->role === 'admin')
                            <td>
                                <form method="POST" action="{{ route('transactions.destroy-sale', $sale) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center text-secondary py-4">Belum ada transaksi penjualan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $sales->withQueryString()->links() }}
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h2 class="store-chart-title mb-3">Pembelian</h2>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead><tr><th>No. Pembelian</th><th>Tanggal</th><th>Supplier</th><th>Produk</th><th>Jumlah</th><th>Harga Beli</th><th>Total</th><th>Catatan</th>@if(Auth::user()->role === 'admin')<th>Aksi</th>@endif</tr></thead>
                <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td class="fw-semibold">{{ $purchase->purchase_number }}</td>
                        <td>{{ $purchase->purchased_at->format('d/m/Y') }}</td>
                        <td>{{ $purchase->supplier_name }}</td>
                        <td>{{ $purchase->product?->name ?? '-' }}</td>
                        <td>{{ $purchase->quantity }}</td>
                        <td>Rp {{ number_format((float) $purchase->purchase_price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format((float) $purchase->total_price, 0, ',', '.') }}</td>
                        <td>{{ $purchase->notes ?? '-' }}</td>
                        @if(Auth::user()->role === 'admin')
                            <td>
                                <form method="POST" action="{{ route('transactions.destroy-purchase', $purchase) }}" onsubmit="return confirm('Hapus pembelian ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-secondary py-4">Belum ada pembelian.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $purchases->withQueryString()->links() }}
    </div>
</div>
@endsection
