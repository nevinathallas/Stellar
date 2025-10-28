@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold mb-3">🪐 Selamat Datang di Stellar Rent</h1>
            <p class="lead text-muted">Sewa Planet untuk Petualangan Luar Angkasa Anda!</p>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <form action="{{ route('home') }}" method="GET">
                <div class="input-group input-group-lg">
                    <input type="text" name="search" class="form-control" placeholder="Cari planet..." value="{{ $search ?? '' }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Units Grid -->
    @if($units->count() > 0)
        <div class="row g-4">
            @foreach($units as $unit)
                <div class="col-md-4 col-lg-3">
                    <div class="card planet-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-planet2 text-primary"></i> {{ $unit->name }}
                                </h5>
                                <span class="badge bg-success status-badge">Available</span>
                            </div>
                            
                            <p class="text-muted small mb-2">
                                <strong>Kode:</strong> {{ $unit->code }}
                            </p>
                            
                            <p class="mb-2">
                                @foreach($unit->categories as $category)
                                    <span class="badge bg-secondary">{{ $category->name }}</span>
                                @endforeach
                            </p>
                            
                            <h6 class="text-primary mb-3">
                                Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}/hari
                            </h6>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('units.show', $unit) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                @auth
                                    @if(auth()->user()->isMember())
                                        <a href="{{ route('member.rentals.create', $unit) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-cart-plus"></i> Sewa Sekarang
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-cart-plus"></i> Login untuk Sewa
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                {{ $units->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Tidak ada planet yang tersedia saat ini.
        </div>
    @endif
</div>
@endsection
