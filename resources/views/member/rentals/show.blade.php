@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-3">
                <a href="{{ route('member.rentals.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow-lg">
                <div class="card-header {{ $rental->isOngoing() ? 'bg-warning' : ($rental->isReturned() ? 'bg-success' : 'bg-danger') }} text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-receipt"></i> Detail Penyewaan
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        @if($rental->isOngoing())
                            <span class="badge bg-warning fs-5 px-4 py-2">
                                <i class="bi bi-hourglass-split"></i> SEDANG BERJALAN
                            </span>
                        @elseif($rental->isReturned())
                            <span class="badge bg-success fs-5 px-4 py-2">
                                <i class="bi bi-check-circle"></i> SUDAH DIKEMBALIKAN
                            </span>
                        @else
                            <span class="badge bg-danger fs-5 px-4 py-2">
                                <i class="bi bi-exclamation-triangle"></i> TERLAMBAT
                            </span>
                        @endif
                    </div>

                    <!-- Unit Info -->
                    <h5 class="mb-3"><i class="bi bi-planet2"></i> Informasi Unit</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Nama Planet</strong></td>
                                <td>: {{ $rental->unit->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode Unit</strong></td>
                                <td>: {{ $rental->unit->code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td>: 
                                    @foreach($rental->unit->categories as $category)
                                        <span class="badge bg-secondary">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Harga/Hari</strong></td>
                                <td>: Rp {{ number_format($rental->unit->price_per_day, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>

                    <hr>

                    <!-- Rental Info -->
                    <h5 class="mb-3"><i class="bi bi-calendar-range"></i> Informasi Sewa</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Tanggal Mulai</strong></td>
                                <td>: {{ $rental->start_date->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Selesai</strong></td>
                                <td>: {{ $rental->end_date->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Durasi</strong></td>
                                <td>: {{ $rental->duration_days }} hari</td>
                            </tr>
                            @if($rental->returned_at)
                                <tr>
                                    <td><strong>Dikembalikan</strong></td>
                                    <td>: {{ $rental->returned_at->format('d F Y H:i') }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>

                    <hr>

                    <!-- Payment Info -->
                    <h5 class="mb-3"><i class="bi bi-cash-stack"></i> Informasi Pembayaran</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Harga Sewa ({{ $rental->duration_days }} hari)</strong></td>
                                <td class="text-end">Rp {{ number_format($rental->unit->price_per_day * $rental->duration_days, 0, ',', '.') }}</td>
                            </tr>
                            @if($rental->fine > 0)
                                <tr class="table-danger">
                                    <td><strong>Denda Keterlambatan ({{ $rental->calculateDaysLate() }} hari)</strong></td>
                                    <td class="text-end">Rp {{ number_format($rental->fine, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr class="table-info fw-bold">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-end">
                                    Rp {{ number_format(($rental->unit->price_per_day * $rental->duration_days) + $rental->fine, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Alert Messages -->
                    @if($rental->isOngoing() && $rental->calculateDaysLate() > 0)
                        <div class="alert alert-danger">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Penyewaan Terlambat!</h6>
                            <p class="mb-0">
                                Anda terlambat <strong>{{ $rental->calculateDaysLate() }} hari</strong> dari tanggal pengembalian.
                                Segera hubungi admin untuk pengembalian unit. 
                                Denda yang harus dibayar: <strong>Rp {{ number_format($rental->calculateFine(), 0, ',', '.') }}</strong>
                            </p>
                        </div>
                    @elseif($rental->isOngoing())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Penyewaan masih berjalan. Hubungi admin untuk pengembalian unit.
                        </div>
                    @elseif($rental->isReturned() && $rental->fine > 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            Unit telah dikembalikan dengan denda keterlambatan sebesar 
                            <strong>Rp {{ number_format($rental->fine, 0, ',', '.') }}</strong>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> 
                            Unit telah dikembalikan tepat waktu tanpa denda.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
