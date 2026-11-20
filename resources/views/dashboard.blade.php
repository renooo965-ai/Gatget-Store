@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="mb-4">
    <div class="store-page-header">
        <div>
            <h1>Dashboard</h1>
            <p class="text-secondary small mb-0">Ringkasan inventaris dan kondisi stok Gadget Store.</p>
        </div>
        @if(Auth::user()->role === 'admin')
            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Tambah Produk</a>
        @endif
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12"><div class="card shadow-sm h-100"><div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h2 class="store-chart-title mb-1">Tren Transaksi 7 Hari</h2>
                <p class="small text-secondary mb-0">Perbandingan nilai penjualan dan pembelian harian.</p>
            </div>
            <span class="badge rounded-pill text-bg-primary px-3 py-2">7 Hari</span>
        </div>
        <div style="height:280px"><canvas id="transactionTrendChart"></canvas></div>
    </div></div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3"><div class="card store-metric h-100" style="--metric-color:#4e73df"><div class="card-body">
        <div class="store-metric-label">Total Produk</div>
        <div class="d-flex justify-content-between align-items-center"><h2 class="store-metric-value mb-0">{{ number_format($totalProducts) }}</h2><i class="bi bi-phone fs-3 text-secondary opacity-25"></i></div>
    </div></div></div>
    <div class="col-sm-6 col-lg-3"><div class="card store-metric h-100" style="--metric-color:#1cc88a"><div class="card-body">
        <div class="store-metric-label">Kategori</div>
        <div class="d-flex justify-content-between align-items-center"><h2 class="store-metric-value mb-0">{{ number_format($totalCategories) }}</h2><i class="bi bi-tags fs-3 text-secondary opacity-25"></i></div>
    </div></div></div>
    <div class="col-sm-6 col-lg-3"><div class="card store-metric h-100" style="--metric-color:#36b9cc"><div class="card-body">
        <div class="store-metric-label">Total Unit Stok</div>
        <div class="d-flex justify-content-between align-items-center"><h2 class="store-metric-value mb-0">{{ number_format($totalStock) }}</h2><i class="bi bi-box-seam fs-3 text-secondary opacity-25"></i></div>
    </div></div></div>
    <div class="col-sm-6 col-lg-3"><div class="card store-metric h-100" style="--metric-color:#f6c23e"><div class="card-body">
        <div class="store-metric-label">Stok Menipis (≤ 5)</div>
        <div class="d-flex justify-content-between align-items-center"><h2 class="store-metric-value mb-0">{{ number_format($lowStockProducts) }}</h2><i class="bi bi-exclamation-triangle fs-3 text-secondary opacity-25"></i></div>
    </div></div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8"><div class="card shadow-sm h-100"><div class="card-body">
        <h2 class="store-chart-title">Jumlah Produk per Kategori</h2>
        <div style="height:300px"><canvas id="categoryChart"></canvas></div>
    </div></div></div>
    <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body">
        <h2 class="store-chart-title">Komposisi Produk per Kategori</h2>
        <div style="height:300px"><canvas id="compositionChart"></canvas></div>
    </div></div></div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8"><div class="card shadow-sm h-100"><div class="card-body">
        <h2 class="store-chart-title">Total Unit Stok per Kategori</h2>
        <div style="height:280px"><canvas id="stockByCategoryChart"></canvas></div>
    </div></div></div>
    <div class="col-md-4"><div class="card shadow-sm h-100"><div class="card-body">
        <h2 class="store-chart-title">Estimasi Nilai Persediaan</h2>
        <div class="h3 fw-bold text-primary">Rp {{ number_format($inventoryValue, 0, ',', '.') }}</div>
        <p class="small text-secondary mb-0">Harga produk × jumlah stok. Ini bukan omzet atau laba penjualan.</p>
    </div></div></div>
</div>

<div class="card shadow-sm"><div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="store-chart-title mb-0">Produk Terbaru</h2>
        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">Lihat semua</a>
    </div>
    <div class="table-responsive"><table class="table align-middle mb-0">
        <thead><tr><th>Kode</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th></tr></thead>
        <tbody>
        @forelse($latestProducts as $product)
            <tr>
                <td>{{ $product->product_name }}</td>
                <td class="fw-semibold">{{ $product->name }}</td>
                <td>{{ $product->category?->name ?? '-' }}</td>
                <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                <td>{{ number_format($product->stock) }}</td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-secondary">Belum ada data produk.</td></tr>
        @endforelse
        </tbody>
    </table></div>
</div></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const categoryLabels = @json($categoryStats->pluck('name')->values());
const productCounts = @json($categoryStats->pluck('product_count')->map(fn ($value) => (int) $value)->values());
const stockTotals = @json($categoryStats->pluck('stock_total')->map(fn ($value) => (int) $value)->values());
const trendLabels = @json($trendLabels);
const salesTrend = @json($salesTrend);
const purchaseTrend = @json($purchaseTrend);
const categoryColors = categoryLabels.map((_, index) =>
    `hsl(${Math.round(index * 360 / Math.max(categoryLabels.length, 1))}, 72%, 58%)`
);

new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: { labels: categoryLabels, datasets: [{
        label: 'Jumlah produk',
        data: productCounts,
        backgroundColor: categoryColors,
        borderColor: categoryColors,
        borderRadius: 6
    }] },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
});

new Chart(document.getElementById('compositionChart'), {
    type: 'doughnut',
    data: { labels: categoryLabels, datasets: [{
        data: productCounts,
        backgroundColor: categoryColors,
        borderColor: '#ffffff',
        borderWidth: 2
    }] },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            tooltip: { callbacks: { label: context => `${context.label}: ${context.parsed} produk` } }
        }
    }
});

new Chart(document.getElementById('stockByCategoryChart'), {
    type: 'bar',
    data: { labels: categoryLabels, datasets: [{
        label: 'Unit stok',
        data: stockTotals,
        backgroundColor: categoryColors,
        borderColor: categoryColors,
        borderRadius: 6
    }] },
    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
});

new Chart(document.getElementById('transactionTrendChart'), {
    type: 'line',
    data: {
        labels: trendLabels,
        datasets: [
            {
                label: 'Penjualan',
                data: salesTrend,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78,115,223,.12)',
                fill: true,
                tension: .38,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#ffffff',
                pointBorderWidth: 2,
                borderWidth: 3
            },
            {
                label: 'Pembelian',
                data: purchaseTrend,
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28,200,138,.08)',
                fill: true,
                tension: .38,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '#ffffff',
                pointBorderWidth: 2,
                borderWidth: 3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { intersect: false, mode: 'index' },
        plugins: {
            legend: { position: 'top', align: 'end' },
            tooltip: {
                callbacks: {
                    label: context => `${context.dataset.label}: Rp ${Number(context.parsed.y).toLocaleString('id-ID')}`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(148,163,184,.12)' },
                ticks: {
                    callback: value => 'Rp ' + Number(value).toLocaleString('id-ID')
                }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
