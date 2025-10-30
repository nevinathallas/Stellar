@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mb-3">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Home
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card planet-card shadow-lg">
                <!-- Gambar Planet -->
                @if($unit->image_url)
                    <img src="{{ $unit->image_url }}" class="card-img-top" alt="{{ $unit->name }}" style="max-height: 400px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-center" style="height: 400px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-planet2 text-white" style="font-size: 8rem;"></i>
                    </div>
                @endif
                
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h2 class="display-5 fw-bold mb-2">
                                {{ $unit->name }}
                            </h2>
                            <p class="text-muted mb-0">Kode Unit: <strong>{{ $unit->code }}</strong></p>
                        </div>
                        <span class="badge bg-success status-badge fs-6">
                            <i class="bi bi-check-circle"></i> Available
                        </span>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Kategori Planet</h5>
                        <div>
                            @foreach($unit->categories as $category)
                                <span class="badge bg-secondary me-2 mb-2 p-2">
                                    <i class="bi bi-tag"></i> {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="mb-3">Harga Sewa</h5>
                        <h3 class="text-primary fw-bold">
                            Rp {{ number_format($unit->price_per_day, 0, ',', '.') }} <small class="text-muted fs-6">/ hari</small>
                        </h3>
                    </div>

                    <hr>

                    <div class="mb-4">
                        <h5 class="mb-3"><i class="bi bi-info-circle"></i> Informasi Penyewaan</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Maksimal durasi sewa: <strong>5 hari</strong></li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success"></i> Maksimal sewa per member: <strong>2 unit</strong></li>
                            <li class="mb-2"><i class="bi bi-exclamation-triangle text-warning"></i> Denda keterlambatan: <strong>Rp 100.000/hari</strong></li>
                            <li class="mb-2"><i class="bi bi-info-circle text-info"></i> Pengembalian hanya bisa dilakukan oleh admin</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-lg sticky-top" style="top: 20px;">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Sewa Planet Ini</h5>
                    
                    @guest
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Anda harus login terlebih dahulu untuk menyewa planet.
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                <i class="bi bi-person-plus"></i> Daftar
                            </a>
                        </div>
                    @else
                        @if(auth()->user()->isAdmin())
                            <div class="alert alert-warning">
                                <i class="bi bi-shield-exclamation"></i> Admin tidak dapat menyewa unit.
                            </div>
                        @else
                            <div class="d-grid">
                                <a href="{{ route('member.rentals.create', $unit) }}" class="btn btn-primary btn-lg">
                                    <i class="bi bi-cart-plus"></i> Sewa Sekarang
                                </a>
                            </div>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
