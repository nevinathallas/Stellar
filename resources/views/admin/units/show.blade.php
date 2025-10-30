@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 mb-3">
            <a href="{{ route('admin.units.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Unit Details -->
        <div class="col-lg-8">
            <div class="card shadow-lg mb-4">
                @if($unit->image_url)
                    <img src="{{ $unit->image_url }}" class="card-img-top" alt="{{ $unit->name }}" 
                         style="max-height: 400px; object-fit: cover;">
                @else
                    <div class="bg-secondary text-center" style="height: 300px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-planet2 text-white" style="font-size: 8rem;"></i>
                    </div>
                @endif
                
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="fw-bold mb-2">{{ $unit->name }}</h2>
                            <p class="text-muted mb-0">Kode: <strong>{{ $unit->code }}</strong></p>
                        </div>
                        <span class="badge {{ $unit->status == 'available' ? 'bg-success' : 'bg-danger' }} fs-6">
                            {{ $unit->status == 'available' ? 'Available' : 'Rented' }}
                        </span>
                    </div>

                    <hr>

                    <!-- Categories -->
                    <div class="mb-3">
                        <h5 class="mb-2">Kategori</h5>
                        @foreach($unit->categories as $category)
                            <span class="badge bg-secondary me-1 mb-1">{{ $category->name }}</span>
                        @endforeach
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <h5 class="mb-2">Harga Sewa</h5>
                        <h3 class="text-primary">
                            Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}
                            <small class="text-muted fs-6">/ hari</small>
                        </h3>
                    </div>

                    <hr>

                    <!-- Actions -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus unit ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Rental History -->
            <div class="card shadow">
                <div class="card-header bg-info">
                    <h5 class="mb-0 text-white"><i class="bi bi-clock-history"></i> Riwayat Penyewaan</h5>
                </div>
                <div class="card-body">
                    @if($unit->rentals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Tanggal Sewa</th>
                                        <th>Durasi</th>
                                        <th>Status</th>
                                        <th>Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unit->rentals()->latest()->get() as $rental)
                                        <tr>
                                            <td>{{ $rental->user->name }}</td>
                                            <td>{{ $rental->start_date->format('d M Y') }}</td>
                                            <td>{{ $rental->duration_days }} hari</td>
                                            <td>
                                                @if($rental->status == 'ongoing')
                                                    <span class="badge bg-warning">Ongoing</span>
                                                @elseif($rental->status == 'returned')
                                                    <span class="badge bg-success">Returned</span>
                                                @else
                                                    <span class="badge bg-danger">Overdue</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($rental->fine > 0)
                                                    <span class="text-danger">
                                                        Rp {{ number_format($rental->fine, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Belum ada riwayat penyewaan untuk unit ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="col-lg-4">
            <div class="card shadow mb-3">
                <div class="card-header bg-primary">
                    <h5 class="mb-0 text-white"><i class="bi bi-graph-up"></i> Statistik</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total Penyewaan</small>
                        <h3 class="mb-0">{{ $unit->rentals->count() }}</h3>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted">Sedang Disewa</small>
                        <h3 class="mb-0">{{ $unit->rentals()->ongoing()->count() }}</h3>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted">Sudah Dikembalikan</small>
                        <h3 class="mb-0">{{ $unit->rentals()->returned()->count() }}</h3>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <small class="text-muted">Total Denda</small>
                        <h3 class="mb-0 text-danger">
                            Rp {{ number_format($unit->rentals->sum('fine'), 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="card shadow">
                <div class="card-header bg-secondary">
                    <h6 class="mb-0 text-white"><i class="bi bi-info-circle"></i> Informasi</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <i class="bi bi-calendar-plus"></i> Dibuat: 
                        <strong>{{ $unit->created_at->format('d M Y H:i') }}</strong>
                    </small>
                    <br>
                    <small class="text-muted">
                        <i class="bi bi-calendar-check"></i> Update: 
                        <strong>{{ $unit->updated_at->format('d M Y H:i') }}</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
