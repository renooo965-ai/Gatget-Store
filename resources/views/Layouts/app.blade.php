<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Gadget Store'))</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/512/3659/3659899.png">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --store-blue: #4f46e5;
            --store-blue-dark: #3730a3;
            --store-canvas: #f3f5fb;
            --store-text: #5a5c69;
        }

        body {
            margin: 0;
            background: var(--store-canvas);
            color: var(--store-text);
            font-family: Figtree, sans-serif;
        }

        .store-layout { display: flex; min-height: 100vh; }
        .store-sidebar {
            width: 14rem;
            flex: 0 0 14rem;
            min-height: 100vh;
            padding: 1rem;
            color: rgba(255,255,255,.82);
            background: linear-gradient(180deg, #4f46e5 0%, #4338ca 72%, #3730a3 100%);
            box-shadow: .35rem 0 1.2rem rgba(67,56,202,.12);
        }
        .store-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .65rem;
            min-height: 3rem;
            margin-bottom: 1rem;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
        }
        .store-brand i { font-size: 1.35rem; }
        .store-sidebar hr { border-color: rgba(255,255,255,.2); }
        .store-nav-label {
            margin: 1.25rem 0 .45rem;
            color: rgba(255,255,255,.55);
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .08em;
        }
        .store-nav-link {
            display: flex;
            align-items: center;
            gap: .7rem;
            margin: .2rem 0;
            padding: .72rem .8rem;
            border-radius: .35rem;
            color: rgba(255,255,255,.8);
            font-size: .88rem;
            text-decoration: none;
        }
        .store-nav-link i { width: 1rem; text-align: center; }
        .store-nav-link:hover, .store-nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.18);
            box-shadow: inset .18rem 0 0 #fff;
        }
        .store-main { min-width: 0; flex: 1; }
        .store-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            min-height: 4.25rem;
            padding: .7rem 1.5rem;
            background: #fff;
            border-bottom: 1px solid #edf0f7;
            box-shadow: 0 .15rem .85rem rgba(58,59,69,.08);
        }
        .store-search { display: flex; width: min(22rem, 55%); }
        .store-search .form-control {
            border: 0;
            background: var(--store-canvas);
            font-size: .85rem;
            border-radius: .5rem 0 0 .5rem;
        }
        .store-search .btn {
            background: var(--store-blue);
            border-color: var(--store-blue);
            border-radius: 0 .5rem .5rem 0;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .store-user { display: flex; align-items: center; gap: .8rem; font-size: .85rem; }
        .store-user a { color: #858796; text-decoration: none; }
        .store-content { padding: 1.5rem; }
        .store-content .card {
            border: 0;
            border-radius: .75rem;
            box-shadow: 0 .25rem 1.35rem rgba(51,65,85,.09);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .store-content .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 .45rem 1.6rem rgba(51,65,85,.12);
        }
        .store-content .card-body { padding: 1rem; }
        .store-content h1, .store-content h2, .store-content h3, .store-content h4, .store-content h5 {
            color: #5a5c69;
        }
        .store-page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }
        .store-page-header h1 { margin: 0; color: #5a5c69; font-size: 1.5rem; font-weight: 500; }
        .store-metric {
            border-left: .25rem solid var(--metric-color, var(--store-blue)) !important;
            position: relative;
            overflow: hidden;
        }
        .store-metric::after {
            content: '';
            position: absolute;
            right: -1.8rem;
            bottom: -2.6rem;
            width: 6rem;
            height: 6rem;
            border-radius: 50%;
            background: var(--metric-color, var(--store-blue));
            opacity: .055;
        }
        .store-metric-label {
            color: var(--metric-color, var(--store-blue));
            font-size: .68rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .store-metric-value { color: #5a5c69; font-size: 1.25rem; font-weight: 700; }
        .store-chart-title { color: var(--store-blue) !important; font-size: .95rem; font-weight: 700; }
        .store-content .btn-primary {
            background: var(--store-blue);
            border-color: var(--store-blue);
            box-shadow: 0 .25rem .65rem rgba(79,70,229,.18);
        }
        .store-content .btn-primary:hover {
            background: var(--store-blue-dark);
            border-color: var(--store-blue-dark);
        }
        .store-content .table {
            --bs-table-bg: transparent;
            border-color: #edf0f7;
        }
        .store-content .table thead th {
            background: #f8f9fd;
            border-bottom-width: 1px;
            color: #6b7280;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .025em;
            text-transform: uppercase;
        }
        .store-content .table tbody td {
            border-color: #f0f2f7;
            font-size: .875rem;
            padding-top: .75rem;
            padding-bottom: .75rem;
        }
        .store-content .form-control, .store-content .form-select {
            border-color: #dee3ef;
            border-radius: .55rem;
            min-height: 2.65rem;
        }
        .store-content .form-control:focus, .store-content .form-select:focus {
            border-color: #a5b4fc;
            box-shadow: 0 0 0 .2rem rgba(79,70,229,.10);
        }

        @media (max-width: 767.98px) {
            .store-sidebar { width: 4.6rem; flex-basis: 4.6rem; padding: .75rem .45rem; }
            .store-brand span, .store-nav-link span, .store-nav-label { display: none; }
            .store-brand { font-size: 0; }
            .store-brand i { font-size: 1.35rem; }
            .store-nav-link { justify-content: center; padding: .75rem .3rem; }
            .store-nav-link i { width: auto; font-size: 1.1rem; }
            .store-topbar { padding: .65rem .8rem; }
            .store-user { gap: .4rem; }
            .store-content { padding: 1rem .75rem; }
        }
    </style>
</head>
<body>
<div class="store-layout">
    @include('layouts.navigation')

    <div class="store-main">
        <header class="store-topbar">
            <form class="store-search" action="{{ route('products.index') }}" method="GET">
                <input class="form-control" type="search" name="q" value="{{ request('q') }}" placeholder="Cari produk...">
                <button class="btn btn-primary" type="submit" aria-label="Cari produk"><i class="bi bi-search"></i></button>
            </form>
            <div class="store-user">
                <a href="{{ route('profile.edit') }}"><i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}</a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary" type="submit">Keluar</button>
                </form>
            </div>
        </header>

        @isset($header)
            <div class="bg-white border-bottom px-4 py-3">{{ $header }}</div>
        @endisset

        <main class="store-content">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot }}
            @endif
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
