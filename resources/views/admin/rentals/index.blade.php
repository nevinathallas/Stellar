@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white"><i class="bi bi-calendar-check"></i> Daftar Semua Rental</h4>
            <div>
                <a href="{{ route('admin.rentals.history') }}" class="btn btn-light btn-sm me-2">
                    <i class="bi bi-clock-history"></i> Riwayat & Cetak
                </a>
                <a href="{{ route('admin.rentals.ongoing') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-clock"></i> Rental Aktif
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form method="GET" action="{{ route('admin.rentals.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Sedang Sewa</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Sudah Kembali</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cari (Member / Planet)</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Ketik nama member atau planet..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Rentals Table -->
            @if($rentals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Member</th>
                                <th>Planet</th>
                                <th>Tanggal Sewa</th>
                                <th>Jatuh Tempo</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Denda</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentals as $rental)
                            <tr>
                                <td>{{ ($rentals->currentPage() - 1) * $rentals->perPage() + $loop->iteration }}</td>
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
                                        @if($rental->status == 'ongoing' && $rental->due_date->isPast())
                                            <br><small class="text-danger">
                                                <i class="bi bi-exclamation-triangle"></i> 
                                                @php
                                                    $diffInHours = $rental->due_date->diffInHours(now());
                                                    if ($diffInHours < 24) {
                                                        echo $diffInHours . ' jam terlambat';
                                                    } else {
                                                        $diffInDays = floor($diffInHours / 24);
                                                        echo $diffInDays . ' hari terlambat';
                                                    }
                                                @endphp
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $rental->duration_days ?? '-' }} hari</td>
                                <td>
                                    @if($rental->status == 'ongoing')
                                        @if($rental->due_date && $rental->due_date->isPast())
                                            <span class="badge bg-danger">Terlambat</span>
                                        @else
                                            <span class="badge bg-warning">Sedang Sewa</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rental->fine > 0)
                                        <span class="text-danger fw-bold">Rp {{ number_format($rental->fine, 0, ',', '.') }}</span>
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
                                        @if($rental->status == 'ongoing')
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#returnModal{{ $rental->id }}"
                                                    title="Kembalikan">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            <!-- Return Modal -->
                            @if($rental->status == 'ongoing')
                            <div class="modal fade" id="returnModal{{ $rental->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-primary">
                                            <h5 class="modal-title text-white">Konfirmasi Pengembalian</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.rentals.return', $rental->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <p>Yakin ingin menandai rental ini sebagai <strong>dikembalikan</strong>?</p>
                                                <ul class="list-unstyled">
                                                    <li><strong>Member:</strong> {{ $rental->user->name }}</li>
                                                    <li><strong>Planet:</strong> {{ $rental->unit->name }}</li>
                                                    <li><strong>Jatuh Tempo:</strong> 
                                                        @if($rental->due_date)
                                                            {{ $rental->due_date->format('d/m/Y') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </li>
                                                    @if($rental->due_date && $rental->due_date->isPast())
                                                        <li class="text-danger">
                                                            <strong>Keterlambatan:</strong> {{ $rental->due_date->diffInDays(now()) }} hari
                                                        </li>
                                                        <li class="text-danger">
                                                            <strong>Denda:</strong> Rp {{ number_format($rental->due_date->diffInDays(now()) * 100000, 0, ',', '.') }}
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Ya, Kembalikan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $rentals->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada data rental ditemukan.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
