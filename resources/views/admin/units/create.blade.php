@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary">
                    <h4 class="mb-0 text-white"><i class="bi bi-plus-circle"></i> Tambah Unit Planet</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.units.store') }}" method="POST">
                        @csrf

                        <!-- Code -->
                        <div class="mb-3">
                            <label for="code" class="form-label">Kode Unit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code') }}" 
                                   placeholder="Contoh: PLN-011" required autofocus>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kode unit harus unik</small>
                        </div>

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Planet <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Contoh: Earth" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Nama boleh sama, yang penting kode berbeda</small>
                        </div>

                        <!-- Image URL -->
                        <div class="mb-3">
                            <label for="image_url" class="form-label">URL Gambar Planet</label>
                            <input type="url" class="form-control @error('image_url') is-invalid @enderror" 
                                   id="image_url" name="image_url" value="{{ old('image_url') }}" 
                                   placeholder="https://example.com/planet.jpg">
                            @error('image_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opsional - URL gambar dari internet (NASA, dll)</small>
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <label for="price_per_day" class="form-label">Harga Sewa per Hari <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control @error('price_per_day') is-invalid @enderror" 
                                       id="price_per_day" name="price_per_day" value="{{ old('price_per_day') }}" 
                                       min="0" step="1000" placeholder="500000" required>
                                @error('price_per_day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="available" {{ old('status', 'available') == 'available' ? 'selected' : '' }}>Available (Tersedia)</option>
                                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Rented (Disewa)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih "Available" agar planet muncul di halaman home</small>
                        </div>

                        <!-- Categories (Multi-select) -->
                        <div class="mb-4">
                            <label class="form-label">Kategori Planet <span class="text-danger">*</span></label>
                            <div class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">
                                @foreach($categories as $category)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" 
                                               name="categories[]" value="{{ $category->id }}" 
                                               id="category{{ $category->id }}"
                                               {{ (is_array(old('categories')) && in_array($category->id, old('categories'))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('categories')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Pilih minimal 1 kategori</small>
                        </div>

                        <hr>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Unit
                            </button>
                            <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
