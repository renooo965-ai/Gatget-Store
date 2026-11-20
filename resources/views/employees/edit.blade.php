@extends('layouts.app')
@section('title', 'Edit Pegawai')
@section('content')
<div class="store-page-header"><h1>Edit Pegawai</h1></div>
<div class="card"><div class="card-body">
<form method="POST" action="{{ route('employees.update', $employee) }}">
@csrf @method('PUT')
@include('employees.form')
<div class="mt-3">
    <button class="btn btn-primary">Simpan Perubahan</button>
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Batal</a>
</div>
</form>
</div></div>
@endsection
