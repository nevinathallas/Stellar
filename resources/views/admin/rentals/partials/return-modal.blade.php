{{-- Return Modal --}}
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">
                    <i class="bi bi-box-arrow-in-down"></i> Kembalikan Unit
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.rentals.return', $rental) }}">
                @csrf
                <div class="modal-body">
                    <p><strong>Planet:</strong> {{ $rental->unit->name }}</p>
                    <p><strong>Member:</strong> {{ $rental->user->name }}</p>
                    <p><strong>Jatuh Tempo:</strong> {{ $rental->due_date ? $rental->due_date->format('d/m/Y') : '-' }}</p>
                    
                    @if($rental->due_date && $rental->due_date->isPast())
                        <div class="alert alert-danger">
                            <strong>Terlambat {{ $rental->due_date->diffInDays(now()) }} hari</strong><br>
                            Denda: Rp {{ number_format($rental->calculateFine(), 0, ',', '.') }}
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Tidak ada denda
                        </div>
                    @endif
                    
                    <p class="text-muted">Yakin ingin mengembalikan unit ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Kembalikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
