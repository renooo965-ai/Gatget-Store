@extends('layouts.app')
@section('title', 'Tambah Penjualan')
@section('content')
<div class="store-page-header"><h1>Tambah Penjualan</h1></div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('transactions.store-sale') }}">
@csrf
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Nomor Invoice</label><input name="invoice_number" value="{{ old('invoice_number', 'INV-' . date('Ymd') . '-' . rand(100,999)) }}" class="form-control" required>@error('invoice_number')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <div class="col-md-4"><label class="form-label">Nama Pelanggan</label><input name="customer_name" value="{{ old('customer_name') }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Tanggal</label><input type="date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Produk</label><select name="product_id" class="form-select" required><option value="">Pilih produk</option>@foreach($products as $product)<option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }} (stok {{ $product->stock }})</option>@endforeach</select>@error('product_id')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <div class="col-md-2"><label class="form-label">Jumlah</label><input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" class="form-control" required></div>
    <div class="col-md-3"><label class="form-label">Pegawai</label><select name="employee_id" class="form-select"><option value="">Tidak ditentukan</option>@foreach($employees as $employee)<option value="{{ $employee->id }}" @selected(old('employee_id') == $employee->id)>{{ $employee->name }}</option>@endforeach</select></div>
    <div class="col-md-3"><label class="form-label">Metode Pembayaran</label><select name="payment_method" class="form-select" required><option value="cash">Cash</option><option value="transfer">Transfer</option><option value="qris">QRIS</option><option value="card">Kartu</option></select></div>
    <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select" required><option value="completed">Selesai</option><option value="pending">Pending</option><option value="cancelled">Dibatalkan</option></select></div>
</div>
@error('quantity')<div class="alert alert-danger mt-3">{{ $message }}</div>@enderror
<div class="mt-3"><button class="btn btn-primary">Simpan Transaksi</button><a href="{{ route('transactions.index') }}" class="btn btn-secondary">Batal</a></div>
</form>
</div></div>
@endsection
