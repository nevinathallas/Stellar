@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-cart-plus"></i> Form Penyewaan Planet</h4>
                </div>
                <div class="card-body p-4">
                    <!-- Unit Info -->
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="bi bi-planet2"></i> {{ $unit->name }}
                        </h5>
                        <p class="mb-1"><strong>Kode:</strong> {{ $unit->code }}</p>
                        <p class="mb-1"><strong>Harga:</strong> Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}/hari</p>
                        <p class="mb-0">
                            <strong>Kategori:</strong> 
                            @foreach($unit->categories as $category)
                                <span class="badge bg-secondary">{{ $category->name }}</span>
                            @endforeach
                        </p>
                    </div>

                    <form method="POST" action="{{ route('member.rentals.store') }}">
                        @csrf
                        <input type="hidden" name="unit_id" value="{{ $unit->id }}">

                        <!-- Start Date -->
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Tanggal Mulai Sewa</label>
                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                   id="start_date" name="start_date" 
                                   value="{{ old('start_date', date('Y-m-d')) }}" 
                                   min="{{ date('Y-m-d') }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Duration -->
                        <div class="mb-3">
                            <label for="duration_days" class="form-label">Durasi Sewa (Hari)</label>
                            <select class="form-select @error('duration_days') is-invalid @enderror" 
                                    id="duration_days" name="duration_days" required>
                                <option value="">Pilih durasi...</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('duration_days') == $i ? 'selected' : '' }}>
                                        {{ $i }} hari - Rp {{ number_format($unit->price_per_day * $i, 0, ',', '.') }}
                                    </option>
                                @endfor
                            </select>
                            @error('duration_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 5 hari per penyewaan</small>
                        </div>

                        <!-- Info -->
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Perhatian!</h6>
                            <ul class="mb-0 small">
                                <li>Setiap member maksimal dapat menyewa <strong>2 unit</strong> secara bersamaan</li>
                                <li>Durasi maksimal sewa adalah <strong>5 hari</strong></li>
                                <li>Keterlambatan pengembalian akan dikenakan denda <strong>Rp 100.000/hari</strong></li>
                                <li>Pengembalian unit hanya dapat dilakukan oleh <strong>Admin</strong></li>
                                <li>Pembayaran dapat dilakukan secara <strong>cash di tempat</strong></li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Konfirmasi Penyewaan
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
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
