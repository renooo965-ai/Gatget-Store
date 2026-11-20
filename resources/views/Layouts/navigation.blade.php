<aside class="store-sidebar">
    <a class="store-brand" href="{{ route('dashboard') }}">
        <i class="bi bi-bag-check"></i>
        <span>Gadget Store</span>
    </a>
    <hr>
    <nav aria-label="Navigasi utama">
        <a class="store-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>
        <div class="store-nav-label">INVENTARIS</div>
        <a class="store-nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
            <i class="bi bi-phone"></i><span>Data Produk</span>
        </a>
        <a class="store-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
            <i class="bi bi-tags"></i><span>Kategori Produk</span>
        </a>
        <div class="store-nav-label">OPERASIONAL</div>
        <a class="store-nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
            <i class="bi bi-receipt"></i><span>Transaksi</span>
        </a>
        <a class="store-nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
            <i class="bi bi-people"></i><span>Pegawai</span>
        </a>
    </nav>
</aside>
