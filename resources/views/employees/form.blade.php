<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Pegawai</label>
        <input type="text" name="name" value="{{ old('name', $employee->name ?? '') }}" class="form-control" required>
        @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $employee->email ?? '') }}" class="form-control">
        @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" class="form-control">
    </div>
    <div class="col-md-4">
        <label class="form-label">Jabatan</label>
        <input type="text" name="position" value="{{ old('position', $employee->position ?? '') }}" class="form-control" placeholder="Kasir, Sales, Gudang" required>
        @error('position')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            <option value="active" @selected(old('status', $employee->status ?? 'active') === 'active')>Aktif</option>
            <option value="inactive" @selected(old('status', $employee->status ?? '') === 'inactive')>Tidak Aktif</option>
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanggal Masuk</label>
        <input type="date" name="hire_date" value="{{ old('hire_date', isset($employee) ? $employee->hire_date?->format('Y-m-d') : '') }}" class="form-control">
    </div>
    <div class="col-md-8">
        <label class="form-label">Alamat</label>
        <textarea name="address" class="form-control" rows="2">{{ old('address', $employee->address ?? '') }}</textarea>
    </div>
</div>
