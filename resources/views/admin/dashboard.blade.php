@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold"><i class="bi bi-speedometer2"></i> Dashboard Admin</h2>
            <p class="text-muted">Selamat datang, {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Units</h6>
                    <h2 class="mb-0">{{ $totalUnits }}</h2>
                    <small>Planet tersedia</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Total Members</h6>
                    <h2 class="mb-0">{{ $totalMembers }}</h2>
                    <small>Anggota terdaftar</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">Active Rentals</h6>
                    <h2 class="mb-0">{{ $activeRentals }}</h2>
                    <small>Sedang disewa</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6 class="card-title">Overdue</h6>
                    <h2 class="mb-0">{{ $overdueRentals }}</h2>
                    <small>Terlambat</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-lightning-fill"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.units.create') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-plus-circle"></i> Tambah Unit
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-success w-100">
                                <i class="bi bi-plus-circle"></i> Tambah Kategori
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.users.create') }}" class="btn btn-outline-info w-100">
                                <i class="bi bi-person-plus"></i> Tambah User
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.rentals.ongoing') }}" class="btn btn-outline-warning w-100">
                                <i class="bi bi-list-check"></i> Unit Disewa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Rentals -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Rental Terbaru</h5>
                </div>
                <div class="card-body">
                    @if($recentRentals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Member</th>
                                        <th>Unit</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRentals as $rental)
                                        <tr>
                                            <td>{{ $rental->user->name }}</td>
                                            <td>{{ $rental->unit->name }}</td>
                                            <td>{{ $rental->start_date->format('d M Y') }}</td>
                                            <td>{{ $rental->end_date->format('d M Y') }}</td>
                                            <td>
                                                @if($rental->status === 'ongoing')
                                                    <span class="badge bg-warning">Ongoing</span>
                                                @elseif($rental->status === 'returned')
                                                    <span class="badge bg-success">Returned</span>
                                                @else
                                                    <span class="badge bg-danger">Overdue</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.rentals.show', $rental) }}" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Belum ada rental.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
