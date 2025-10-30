{{-- Informasi Detail Rental --}}
<div class="card shadow mb-4">
    <div class="card-header bg-info">
        <h5 class="mb-0 text-white"><i class="bi bi-file-text"></i> Detail Rental</h5>
    </div>
    <div class="card-body">
        <!-- Member Info -->
        <h6 class="text-muted mb-2">Informasi Member</h6>
        <p class="mb-1"><strong>Nama:</strong> {{ $rental->user->name }}</p>
        <p class="mb-1"><strong>Email:</strong> {{ $rental->user->email }}</p>
        <p class="mb-3">
            <strong>Role:</strong> 
            <span class="badge bg-{{ $rental->user->role == 'admin' ? 'danger' : 'primary' }}">
                {{ ucfirst($rental->user->role) }}
            </span>
        </p>

        <hr>

        <!-- Planet Info -->
        <h6 class="text-muted mb-2">Informasi Planet</h6>
        <p class="mb-1"><strong>Nama:</strong> {{ $rental->unit->name }}</p>
        <p class="mb-1"><strong>Kode:</strong> {{ $rental->unit->code }}</p>
        <p class="mb-3">
            <strong>Status:</strong> 
            <span class="badge bg-{{ $rental->unit->status == 'available' ? 'success' : 'danger' }}">
                {{ $rental->unit->status == 'available' ? 'Tersedia' : 'Disewa' }}
            </span>
        </p>

        <hr>

        <!-- Rental Info -->
        <h6 class="text-muted mb-2">Informasi Rental</h6>
        <p class="mb-1">
            <strong>Tanggal Sewa:</strong> 
            {{ $rental->rental_date ? $rental->rental_date->format('d/m/Y') : '-' }}
        </p>
        <p class="mb-1">
            <strong>Jatuh Tempo:</strong> 
            {{ $rental->due_date ? $rental->due_date->format('d/m/Y') : '-' }}
        </p>
        <p class="mb-3">
            <strong>Durasi:</strong> 
            <span class="badge bg-secondary">{{ $rental->duration_days ?? '-' }} hari</span>
        </p>

        {{-- Request Pengembalian dari Member --}}
        @if($rental->hasPendingReturnRequest())
            <hr>
            <div class="alert alert-warning border-warning">
                <h6 class="alert-heading">
                    <i class="bi bi-bell-fill"></i> Request Pengembalian dari Member
                </h6>
                <p class="mb-2">
                    <strong>Waktu Request:</strong> {{ $rental->return_requested_at->format('d F Y, H:i') }} WIB
                </p>
                
                @if($rental->payment_proof)
                    <div class="mb-2">
                        <strong>Bukti Pembayaran:</strong><br>
                        <a href="{{ asset('storage/' . $rental->payment_proof) }}" target="_blank" class="btn btn-sm btn-primary mt-2">
                            <i class="bi bi-image"></i> Lihat Bukti Pembayaran
                        </a>
                    </div>
                @endif
                
                @if($rental->notes)
                    <div class="mb-2">
                        <strong>Catatan dari Member:</strong><br>
                        <div class="bg-light p-2 rounded border mt-1">
                            <em>"{{ $rental->notes }}"</em>
                        </div>
                    </div>
                @endif

                <div class="mt-3">
                    <strong>Total Pembayaran yang Harus Dibayar Member:</strong><br>
                    <table class="table table-sm table-borderless mb-0 mt-1">
                        <tr>
                            <td width="150">Biaya Sewa:</td>
                            <td class="text-end">Rp {{ number_format($rental->unit->price_per_day * $rental->duration_days, 0, ',', '.') }}</td>
                        </tr>
                        @if($rental->calculateDaysLate() > 0)
                            <tr class="text-danger">
                                <td>Denda ({{ $rental->calculateDaysLate() }} hari):</td>
                                <td class="text-end">Rp {{ number_format($rental->calculateFine(), 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr class="fw-bold border-top">
                            <td>TOTAL:</td>
                            <td class="text-end text-primary">
                                Rp {{ number_format(($rental->unit->price_per_day * $rental->duration_days) + $rental->calculateFine(), 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="alert alert-info mb-0 mt-3">
                    <small>
                        <i class="bi bi-info-circle"></i> 
                        Member menunggu verifikasi dari Admin untuk mengembalikan planet.
                    </small>
                </div>
            </div>
        @endif

        @if($rental->status == 'returned')
            <hr>
            <h6 class="text-muted mb-2">Informasi Pengembalian</h6>
            <p class="mb-1">
                <strong>Tanggal Kembali:</strong> 
                {{ $rental->return_date ? $rental->return_date->format('d/m/Y') : '-' }}
            </p>
            <p class="mb-0">
                <strong>Denda:</strong> 
                <span class="text-{{ ($rental->fine ?? 0) > 0 ? 'danger' : 'success' }} fw-bold">
                    Rp {{ number_format($rental->fine ?? 0, 0, ',', '.') }}
                </span>
            </p>
        @endif
    </div>
</div>
