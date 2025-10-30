@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold"><i class="bi bi-list-check"></i> Daftar Rental Saya</h2>
            <p class="text-muted">Semua riwayat penyewaan planet Anda</p>
        </div>
    </div>

    @if($rentals->count() > 0)
        <div class="row g-3">
            @foreach($rentals as $rental)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 {{ $rental->isOngoing() ? 'border-primary' : '' }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-planet2"></i> {{ $rental->unit->name }}
                                </h5>
                                @if($rental->isOngoing())
                                    <span class="badge bg-warning">Ongoing</span>
                                @elseif($rental->isReturned())
                                    <span class="badge bg-success">Returned</span>
                                @else
                                    <span class="badge bg-danger">Overdue</span>
                                @endif
                            </div>

                            <p class="text-muted small mb-2">
                                <strong>Kode:</strong> {{ $rental->unit->code }}
                            </p>

                            <p class="mb-1 small">
                                <i class="bi bi-calendar-check"></i> 
                                <strong>Mulai:</strong> {{ $rental->start_date->format('d M Y') }}
                            </p>
                            <p class="mb-1 small">
                                <i class="bi bi-calendar-x"></i> 
                                <strong>Selesai:</strong> {{ $rental->end_date->format('d M Y') }}
                            </p>
                            <p class="mb-2 small">
                                <i class="bi bi-hourglass-split"></i> 
                                <strong>Durasi:</strong> {{ $rental->duration_days }} hari
                            </p>

                            @if($rental->returned_at)
                                <p class="mb-2 small text-success">
                                    <i class="bi bi-check-circle"></i> 
                                    Dikembalikan: {{ $rental->returned_at->format('d M Y H:i') }}
                                </p>
                            @endif

                            @if($rental->fine > 0)
                                <div class="alert alert-danger p-2 mb-2">
                                    <small>
                                        <i class="bi bi-exclamation-triangle"></i> 
                                        <strong>Denda:</strong> Rp {{ number_format($rental->fine, 0, ',', '.') }}
                                    </small>
                                </div>
                            @endif

                            @if($rental->calculateDaysLate() > 0 && !$rental->isReturned())
                                <div class="alert alert-warning p-2 mb-2">
                                    <small>
                                        <i class="bi bi-exclamation-triangle"></i> 
                                        Terlambat {{ $rental->calculateDaysLate() }} hari
                                    </small>
                                </div>
                            @endif

                            <div class="d-grid">
                                <a href="{{ route('member.rentals.show', $rental) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mt-4">
            <div class="col-12">
                {{ $rentals->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle"></i> Anda belum memiliki riwayat penyewaan. 
            <br><br>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="bi bi-rocket"></i> Mulai Sewa Planet Sekarang!
            </a>
        </div>
    @endif
</div>
@endsection
