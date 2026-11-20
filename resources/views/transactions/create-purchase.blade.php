@extends('layouts.app')
@section('title', 'Tambah Pembelian')
@section('content')
<div class="store-page-header"><h1>Tambah Pembelian</h1></div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('transactions.store-purchase') }}">
@csrf
<div class="row g-3">
    <div class="col-md-4"><label class="form-label">Nomor Pembelian</label><input name="purchase_number" value="{{ old('purchase_number', 'PO-' . date('Ymd') . '-' . rand(100,999)) }}" class="form-control" required>@error('purchase_number')<div class="text-danger small">{{ $message }}</div>@enderror</div>
    <div class="col-md-4"><label class="form-label">Nama Supplier</label><input name="supplier_name" value="{{ old('supplier_name') }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Tanggal</label><input type="date" name="purchased_at" value="{{ old('purchased_at', date('Y-m-d')) }}" class="form-control" required></div>
    <div class="col-md-5"><label class="form-label">Produk</label><select name="product_id" class="form-select" required><option value="">Pilih produk</option>@foreach($products as $product)<option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>{{ $product->name }} (stok {{ $product->stock }})</option>@endforeach</select></div>
    <div class="col-md-2"><label class="form-label">Jumlah</label><input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" class="form-control" required></div>
    <div class="col-md-5"><label class="form-label">Harga Beli per Unit</label><input type="number" name="purchase_price" value="{{ old('purchase_price') }}" min="0" step="0.01" class="form-control" required></div>
    <div class="col-12"><label class="form-label">Catatan</label><textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea></div>
</div>
<div class="mt-3"><button class="btn btn-primary">Simpan Pembelian</button><a href="{{ route('transactions.index') }}" class="btn btn-secondary">Batal</a></div>
</form>
</div></div>
@endsection
