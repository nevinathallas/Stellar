{{-- Actions Sidebar --}}
<div class="card shadow mb-4">
    <div class="card-header bg-primary">
        <h5 class="mb-0 text-white"><i class="bi bi-gear"></i> Aksi</h5>
    </div>
    <div class="card-body">
        @if($rental->status == 'ongoing')
            <!-- Status Badge -->
            <div class="alert alert-{{ $rental->due_date && $rental->due_date->isPast() ? 'danger' : 'warning' }} text-center">
                @if($rental->due_date && $rental->due_date->isPast())
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                    <h5 class="mt-2">TERLAMBAT</h5>
                    <p class="mb-0">{{ $rental->calculateDaysLate() }} hari</p>
                @else
                    <i class="bi bi-clock fs-1"></i>
                    <h5 class="mt-2">SEDANG SEWA</h5>
                    @if($rental->due_date)
                        <p class="mb-0">Sisa {{ (int) now()->diffInDays($rental->due_date) }} hari</p>
                    @endif
                @endif
            </div>

            <!-- Return Button -->
            <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#returnModal">
                <i class="bi bi-box-arrow-in-down"></i> Kembalikan Unit
            </button>

            <!-- Potential Fine Info -->
            @if($rental->due_date && $rental->due_date->isPast())
                <div class="alert alert-danger">
                    <strong>Denda Potensial:</strong><br>
                    Rp {{ number_format($rental->calculateFine(), 0, ',', '.') }}
                </div>
            @endif
        @else
            <!-- Returned Status -->
            <div class="alert alert-success text-center">
                <i class="bi bi-check-circle fs-1"></i>
                <h5 class="mt-2">SELESAI</h5>
                <p class="mb-0">Unit sudah dikembalikan</p>
            </div>
        @endif

        <!-- Print Invoice Button -->
        <a href="{{ route('admin.rentals.print', $rental) }}" class="btn btn-info w-100 mb-2" target="_blank">
            <i class="bi bi-printer"></i> Cetak Invoice PDF
        </a>

        <!-- Back Button -->
        <a href="{{ route('admin.rentals.index') }}" class="btn btn-secondary w-100">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
