@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold"><i class="bi bi-house-door"></i> Dashboard Member</h2>
            <p class="text-muted">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <!-- Active Rentals -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-cart-check"></i> Planet yang Sedang Disewa ({{ $activeRentals->count() }}/2)</h5>
                </div>
                <div class="card-body">
                    @if($activeRentals->count() > 0)
                        <div class="row g-3">
                            @foreach($activeRentals as $rental)
                                <div class="col-md-6">
                                    <div class="card border-primary">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <i class="bi bi-planet2 text-primary"></i> {{ $rental->unit->name }}
                                            </h5>
                                            <p class="text-muted small mb-2">Kode: {{ $rental->unit->code }}</p>
                                            
                                            <div class="mb-2">
                                                @foreach($rental->unit->categories as $category)
                                                    <span class="badge bg-secondary">{{ $category->name }}</span>
                                                @endforeach
                                            </div>

                                            <hr>
                                            
                                            <p class="mb-1"><strong>Tanggal Mulai:</strong> {{ $rental->start_date->format('d M Y') }}</p>
                                            <p class="mb-1"><strong>Tanggal Selesai:</strong> {{ $rental->end_date->format('d M Y') }}</p>
                                            <p class="mb-1"><strong>Durasi:</strong> {{ $rental->duration_days }} hari</p>
                                            <p class="mb-3"><strong>Total Harga:</strong> Rp {{ number_format($rental->unit->price_per_day * $rental->duration_days, 0, ',', '.') }}</p>

                                            @if($rental->calculateDaysLate() > 0)
                                                <div class="alert alert-danger mb-2">
                                                    <i class="bi bi-exclamation-triangle"></i> Terlambat {{ $rental->calculateDaysLate() }} hari! 
                                                    Denda: Rp {{ number_format($rental->calculateFine(), 0, ',', '.') }}
                                                </div>
                                            @endif

                                            <a href="{{ route('member.rentals.show', $rental) }}" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-eye"></i> Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Anda belum menyewa planet. 
                            <a href="{{ route('home') }}" class="alert-link">Jelajahi planet sekarang!</a>
                        </div>
                    @endif

                    @if($activeRentals->count() < 2)
                        <div class="text-center mt-3">
                            <a href="{{ route('home') }}" class="btn btn-success">
                                <i class="bi bi-plus-circle"></i> Sewa Planet Lain
                            </a>
                        </div>
                    @else
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Anda sudah mencapai batas maksimal 2 unit yang disewa. 
                            Silakan kembalikan unit terlebih dahulu untuk menyewa yang lain.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Rental History -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Rental Terakhir</h5>
                </div>
                <div class="card-body">
                    @if($rentalHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Unit</th>
                                        <th>Tanggal</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                        <th>Denda</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rentalHistory as $rental)
                                        <tr>
                                            <td>{{ $rental->unit->name }}</td>
                                            <td>{{ $rental->start_date->format('d M Y') }} - {{ $rental->end_date->format('d M Y') }}</td>
                                            <td>{{ $rental->duration_days }} hari</td>
                                            <td>
                                                @if($rental->status === 'returned')
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @else
                                                    <span class="badge bg-danger">Overdue</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($rental->fine > 0)
                                                    <span class="text-danger">Rp {{ number_format($rental->fine, 0, ',', '.') }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('member.rentals.show', $rental) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('member.rentals.index') }}" class="btn btn-outline-primary">
                                Lihat Semua Riwayat <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada riwayat rental.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
