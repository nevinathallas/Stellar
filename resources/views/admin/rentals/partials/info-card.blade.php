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
