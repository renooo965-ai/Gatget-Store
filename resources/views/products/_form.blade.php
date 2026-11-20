<div class="row g-3">
<div class="col-md-6">
<label>Kode produk</label>
<input name="product_name" class="form-control" value="{{ old('product_name',$product->product_name ?? '') }}">
@error('product_name')<small class="text-danger">{{ $message }}</small>@enderror
</div>
<div class="col-md-6">
<label>Nama produk</label>
<input name="name" class="form-control" value="{{ old('name',$product->name ?? '') }}">
@error('name')<small class="text-danger">{{ $message }}</small>@enderror
</div>
<div class="col-md-6">
<label>Kategori</label>
<select name="category_id" class="form-select">
    <option value="">Pilih kategori</option>
@foreach($categories as $category)
<option value="{{ $category->id }}" @selected(old('category_id',$product->category_id ?? '') == $category->id)>{{ $category->name
}}</option>
@endforeach
</select>
@error('category_id')<small class="text-danger">{{ $message }}</small>@enderror
</div>
<div class="col-md-6">
<label>Merek</label>
<input name="brand" class="form-control" value="{{ old('brand',$product->brand ?? '') }}">
</div>
<div class="col-md-6">
<label>Harga</label>
<input type="number" min="0" step="0.01" name="price" class="form-control" value="{{ old('price',$product->price ?? '') }}">
</div>
<div class="col-md-6">
<label>Gambar Produk</label>
<input type="file" name="image" class="form-control" accept="image/*">
<small class="text-muted">Format: JPG, JPEG, PNG, WEBP. Maksimal 5MB.</small>
@error('image')
<small class="text-danger">{{ $message }}</small>
@enderror
@if(!empty($product->image))
<div class="mt-2">
    <img src="{{ asset('storage/'.$product->image) }}" alt="Gambar Produk" class="img-thumbnail" style="max-width: 100px;">
</div>
@endif
<div class="col-12">
<label>Deskripsi</label>
<textarea name="description" class="form-control">{{ old('description',$product->description ?? '') }}</textarea>
</div>
</div>