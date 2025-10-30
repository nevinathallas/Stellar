@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-warning d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white"><i class="bi bi-clock"></i> Rental Sedang Berlangsung</h4>
            <a href="{{ route('admin.rentals.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-list"></i> Semua Rental
            </a>
        </div>
        <div class="card-body">
            @if($ongoingRentals->count() > 0)
                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h3 class="text-warning mb-0">{{ $ongoingRentals->count() }}</h3>
                                <small class="text-muted">Total Sedang Sewa</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h3 class="text-danger mb-0">{{ $ongoingRentals->filter(fn($r) => $r->due_date && $r->due_date->isPast())->count() }}</h3>
                                <small class="text-muted">Terlambat</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h3 class="text-success mb-0">{{ $ongoingRentals->filter(fn($r) => $r->due_date && !$r->due_date->isPast())->count() }}</h3>
                                <small class="text-muted">Tepat Waktu</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ongoing Rentals Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Planet</th>
                                <th>Tanggal Sewa</th>
                                <th>Jatuh Tempo</th>
                                <th>Sisa Waktu</th>
                                <th>Denda Potensial</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ongoingRentals->sortBy('due_date') as $rental)
                            <tr class="{{ $rental->due_date->isPast() ? 'table-danger' : '' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $rental->user->name }}</strong><br>
                                    <small class="text-muted">{{ $rental->user->email }}</small>
                                </td>
                                <td>
                                    <strong>{{ $rental->unit->name }}</strong><br>
                                    <small class="text-muted">{{ $rental->unit->code }}</small>
                                </td>
                                <td>
                                    @if($rental->rental_date)
                                        {{ $rental->rental_date->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rental->due_date)
                                        {{ $rental->due_date->format('d/m/Y') }}
                                        @if($rental->due_date->isPast())
                                            <br><span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle"></i> Lewat
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rental->due_date)
                                        @if($rental->due_date->isPast())
                                            <span class="text-danger fw-bold">
                                                Terlambat {{ (int) $rental->due_date->diffInDays(now()) }} hari
                                            </span>
                                        @else
                                            <span class="text-success">
                                                {{ (int) now()->diffInDays($rental->due_date) }} hari lagi
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rental->due_date && $rental->due_date->isPast())
                                        <span class="text-danger fw-bold">
                                            Rp {{ number_format((int) $rental->due_date->diffInDays(now()) * 100000, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.rentals.show', $rental->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#returnModal{{ $rental->id }}"
                                                title="Kembalikan">
                                            <i class="bi bi-arrow-return-left"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Return Modal -->
                            <div class="modal fade" id="returnModal{{ $rental->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success">
                                            <h5 class="modal-title text-white">Konfirmasi Pengembalian</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.rentals.return', $rental->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle"></i> 
                                                    <strong>Pengembalian Planet:</strong> {{ $rental->unit->name }}
                                                </div>
                                                
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Member:</strong></td>
                                                        <td>{{ $rental->user->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Tanggal Sewa:</strong></td>
                                                        <td>
                                                            @if($rental->rental_date)
                                                                {{ $rental->rental_date->format('d/m/Y') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Jatuh Tempo:</strong></td>
                                                        <td>
                                                            @if($rental->due_date)
                                                                {{ $rental->due_date->format('d/m/Y') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Durasi:</strong></td>
                                                        <td>{{ $rental->duration_days ?? '-' }} hari</td>
                                                    </tr>
                                                    @if($rental->due_date && $rental->due_date->isPast())
                                                        <tr class="table-danger">
                                                            <td><strong>Keterlambatan:</strong></td>
                                                            <td class="text-danger fw-bold">{{ (int) $rental->due_date->diffInDays(now()) }} hari</td>
                                                        </tr>
                                                        <tr class="table-danger">
                                                            <td><strong>Denda:</strong></td>
                                                            <td class="text-danger fw-bold">Rp {{ number_format((int) $rental->due_date->diffInDays(now()) * 100000, 0, ',', '.') }}</td>
                                                        </tr>
                                                    @else
                                                        <tr class="table-success">
                                                            <td><strong>Status:</strong></td>
                                                            <td class="text-success">Tepat Waktu (Tidak ada denda)</td>
                                                        </tr>
                                                    @endif
                                                </table>

                                                <p class="mb-0">Yakin ingin mengembalikan planet ini?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bi bi-check-circle"></i> Ya, Kembalikan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-success">
                    <i class="bi bi-check-circle"></i> Tidak ada rental yang sedang berlangsung. Semua planet tersedia!
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
