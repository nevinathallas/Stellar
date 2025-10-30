@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- User Details -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-info">
                    <h4 class="mb-0 text-white"><i class="bi bi-person-circle"></i> Detail User</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="bi bi-person-circle" style="font-size: 80px; color: #3b82f6;"></i>
                    </div>

                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">Nama:</td>
                            <td class="text-end"><strong>{{ $user->name }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td class="text-end">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Role:</td>
                            <td class="text-end">
                                @if($user->role == 'admin')
                                    <span class="badge bg-danger">Admin</span>
                                @else
                                    <span class="badge bg-primary">Member</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Terdaftar:</td>
                            <td class="text-end">{{ $user->created_at->format('d M Y') }}</td>
                        </tr>
                    </table>

                    <hr>

                    <div class="d-grid gap-2">
                        @if($user->id != auth()->id())
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="bi bi-info-circle"></i> Ini adalah akun Anda sendiri
                            </div>
                        @endif
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow">
                <div class="card-header bg-dark">
                    <h5 class="mb-0 text-white"><i class="bi bi-bar-chart"></i> Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <h3 class="text-primary mb-0">{{ $user->rentals->count() }}</h3>
                            <small class="text-muted">Total Rental</small>
                        </div>
                        <div class="col-6 mb-3">
                            <h3 class="text-warning mb-0">{{ $user->rentals->where('status', 'ongoing')->count() }}</h3>
                            <small class="text-muted">Sedang Sewa</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-success mb-0">{{ $user->rentals->where('status', 'returned')->count() }}</h3>
                            <small class="text-muted">Selesai</small>
                        </div>
                        <div class="col-6">
                            <h3 class="text-danger mb-0">
                                Rp {{ number_format(
                                    $user->rentals->sum(function($rental) {
                                        // Kalau sudah ada fine tersimpan, pakai itu
                                        if ($rental->fine > 0) {
                                            return $rental->fine;
                                        }
                                        // Kalau ongoing dan telat, hitung real-time
                                        if ($rental->status == 'ongoing' && $rental->calculateDaysLate() > 0) {
                                            return $rental->calculateFine();
                                        }
                                        return 0;
                                    }), 0, ',', '.') }}
                            </h3>
                            <small class="text-muted">Total Denda</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rental History -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-secondary">
                    <h4 class="mb-0 text-white"><i class="bi bi-clock-history"></i> Riwayat Rental</h4>
                </div>
                <div class="card-body">
                    @if($user->rentals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Planet</th>
                                        <th>Tanggal Sewa</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                        <th>Denda</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->rentals->sortByDesc('created_at') as $rental)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
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
                                            @if($rental->return_date)
                                                {{ $rental->return_date->format('d/m/Y') }}
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
                                                {{-- Denda sudah tersimpan (rental returned) --}}
                                                <span class="text-danger">Rp {{ number_format($rental->fine, 0, ',', '.') }}</span>
                                            @elseif($rental->status == 'ongoing' && $rental->calculateDaysLate() > 0)
                                                {{-- Rental ongoing tapi telat - hitung real-time --}}
                                                <span class="text-danger">Rp {{ number_format($rental->calculateFine(), 0, ',', '.') }}</span>
                                            @else
                                                {{-- Tidak ada denda --}}
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.rentals.show', $rental->id) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> User ini belum pernah melakukan rental.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
