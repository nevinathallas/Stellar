@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">
                <i class="bi bi-clock-history"></i> Riwayat Peminjaman Unit
            </h4>
            <div>
                <button onclick="window.print()" class="btn btn-light me-2">
                    <i class="bi bi-printer"></i> Cetak
                </button>
                <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-light">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <div class="row mb-4 no-print">
                <div class="col-12">
                    <form action="{{ route('admin.rentals.history') }}" method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="user_id" class="form-label">Filter User:</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">-- Semua User --</option>
                                @foreach(\App\Models\User::where('role', 'member')->orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label">Dari Tanggal:</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" 
                                   value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label">Sampai Tanggal:</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" 
                                   value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Filter Active -->
            @if(request('user_id') || request('start_date') || request('end_date'))
                <div class="alert alert-info no-print">
                    <strong>Filter Aktif:</strong>
                    @if(request('user_id'))
                        User: <strong>{{ \App\Models\User::find(request('user_id'))->name }}</strong>
                    @endif
                    @if(request('start_date'))
                        | Dari: <strong>{{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}</strong>
                    @endif
                    @if(request('end_date'))
                        | Sampai: <strong>{{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}</strong>
                    @endif
                    <a href="{{ route('admin.rentals.history') }}" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="bi bi-x-circle"></i> Reset Filter
                    </a>
                </div>
            @endif

            <!-- Print Header (hanya muncul saat print) -->
            <div class="print-only text-center mb-4">
                <h2>LAPORAN RIWAYAT PEMINJAMAN UNIT</h2>
                <h4>STELLAR RENT - SISTEM SEWA PLANET</h4>
                <p class="mb-1">Tanggal Cetak: {{ now()->format('d F Y, H:i') }} WIB</p>
                @if(request('user_id'))
                    <p class="mb-1">User: <strong>{{ \App\Models\User::find(request('user_id'))->name }}</strong></p>
                @endif
                @if(request('start_date') || request('end_date'))
                    <p class="mb-0">
                        Periode: 
                        {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '-' }}
                        s/d
                        {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : '-' }}
                    </p>
                @endif
                <hr>
            </div>

            @if($rentals->count() > 0)
                <!-- Summary Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <h3 class="text-info mb-0">{{ $rentals->count() }}</h3>
                                <small class="text-muted">Total Transaksi</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h3 class="text-warning mb-0">{{ $rentals->where('status', 'ongoing')->count() }}</h3>
                                <small class="text-muted">Sedang Sewa</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h3 class="text-success mb-0">{{ $rentals->where('status', 'returned')->count() }}</h3>
                                <small class="text-muted">Selesai</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h3 class="text-danger mb-0">
                                    Rp {{ number_format($rentals->sum(function($rental) {
                                        if ($rental->fine > 0) {
                                            return $rental->fine;
                                        }
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

                <!-- Rental History Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="3%">#</th>
                                <th width="15%">Member</th>
                                <th width="15%">Planet</th>
                                <th width="10%">Tanggal Sewa</th>
                                <th width="10%">Jatuh Tempo</th>
                                <th width="10%">Tanggal Kembali</th>
                                <th width="7%">Durasi</th>
                                <th width="10%">Status</th>
                                <th width="12%">Denda</th>
                                <th width="8%" class="no-print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rentals as $rental)
                            <tr>
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
                                <td class="text-center">{{ $rental->duration_days ?? '-' }} hari</td>
                                <td>
                                    @if($rental->status == 'ongoing')
                                        @if($rental->due_date && $rental->due_date->isPast())
                                            <span class="badge bg-danger">Terlambat</span>
                                        @else
                                            <span class="badge bg-warning">Sedang Sewa</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">Dikembalikan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rental->fine > 0)
                                        <span class="text-danger fw-bold">Rp {{ number_format($rental->fine, 0, ',', '.') }}</span>
                                    @elseif($rental->status == 'ongoing' && $rental->calculateDaysLate() > 0)
                                        <span class="text-danger fw-bold">Rp {{ number_format($rental->calculateFine(), 0, ',', '.') }}</span>
                                        <br><small class="text-muted">({{ $rental->calculateDaysLate() }} hari telat)</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="no-print">
                                    <a href="{{ route('admin.rentals.show', $rental->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="8" class="text-end fw-bold">TOTAL DENDA:</td>
                                <td colspan="2" class="fw-bold text-danger">
                                    Rp {{ number_format($rentals->sum(function($rental) {
                                        if ($rental->fine > 0) {
                                            return $rental->fine;
                                        }
                                        if ($rental->status == 'ongoing' && $rental->calculateDaysLate() > 0) {
                                            return $rental->calculateFine();
                                        }
                                        return 0;
                                    }), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Print Footer -->
                <div class="print-only mt-5">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1">Mengetahui,</p>
                            <br><br><br>
                            <p class="mb-0">_______________________</p>
                            <p class="mb-0"><strong>Admin Stellar Rent</strong></p>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-1">{{ now()->format('d F Y') }}</p>
                            <br><br><br>
                            <p class="mb-0">_______________________</p>
                            <p class="mb-0"><strong>Manager</strong></p>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle fs-1"></i>
                    <h5 class="mt-3">Tidak ada data riwayat peminjaman</h5>
                    <p class="mb-0">Silakan ubah filter atau tambah data rental baru.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Print Styles */
    @media print {
        .no-print {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-header {
            background-color: #fff !important;
            color: #000 !important;
            border-bottom: 2px solid #000 !important;
        }
        body {
            margin: 0;
            padding: 15px;
        }
        .table {
            font-size: 12px;
        }
        .badge {
            border: 1px solid #000;
            padding: 2px 5px;
        }
    }
    
    .print-only {
        display: none;
    }
</style>
@endsection
