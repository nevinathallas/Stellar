@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-3">
                <a href="{{ route('member.rentals.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow-lg">
                <div class="card-header {{ $rental->isOngoing() ? 'bg-warning' : ($rental->isReturned() ? 'bg-success' : 'bg-danger') }} text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-receipt"></i> Detail Penyewaan
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        @if($rental->isOngoing())
                            <span class="badge bg-warning fs-5 px-4 py-2">
                                <i class="bi bi-hourglass-split"></i> SEDANG BERJALAN
                            </span>
                        @elseif($rental->isReturned())
                            <span class="badge bg-success fs-5 px-4 py-2">
                                <i class="bi bi-check-circle"></i> SUDAH DIKEMBALIKAN
                            </span>
                        @else
                            <span class="badge bg-danger fs-5 px-4 py-2">
                                <i class="bi bi-exclamation-triangle"></i> TERLAMBAT
                            </span>
                        @endif
                    </div>

                    <!-- Unit Info -->
                    <h5 class="mb-3"><i class="bi bi-planet2"></i> Informasi Unit</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Nama Planet</strong></td>
                                <td>: {{ $rental->unit->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kode Unit</strong></td>
                                <td>: {{ $rental->unit->code }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori</strong></td>
                                <td>: 
                                    @foreach($rental->unit->categories as $category)
                                        <span class="badge bg-secondary">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Harga/Hari</strong></td>
                                <td>: Rp {{ number_format($rental->unit->price_per_day, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>

                    <hr>

                    <!-- Rental Info -->
                    <h5 class="mb-3"><i class="bi bi-calendar-range"></i> Informasi Sewa</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-borderless">
                            <tr>
                                <td width="150"><strong>Tanggal Mulai</strong></td>
                                <td>: {{ $rental->start_date->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Selesai</strong></td>
                                <td>: {{ $rental->end_date->format('d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Durasi</strong></td>
                                <td>: {{ $rental->duration_days }} hari</td>
                            </tr>
                            @if($rental->returned_at)
                                <tr>
                                    <td><strong>Dikembalikan</strong></td>
                                    <td>: {{ $rental->returned_at->format('d F Y H:i') }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>

                    <hr>

                    <!-- Payment Info -->
                    <h5 class="mb-3"><i class="bi bi-cash-stack"></i> Informasi Pembayaran</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <tr>
                                <td><strong>Harga Sewa ({{ $rental->duration_days }} hari)</strong></td>
                                <td class="text-end">Rp {{ number_format($rental->unit->price_per_day * $rental->duration_days, 0, ',', '.') }}</td>
                            </tr>
                            @if($rental->fine > 0)
                                <tr class="table-danger">
                                    <td><strong>Denda Keterlambatan ({{ $rental->calculateDaysLate() }} hari)</strong></td>
                                    <td class="text-end">Rp {{ number_format($rental->fine, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            <tr class="table-info fw-bold">
                                <td><strong>TOTAL</strong></td>
                                <td class="text-end">
                                    Rp {{ number_format(($rental->unit->price_per_day * $rental->duration_days) + $rental->fine, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Alert Messages -->
                    @if($rental->hasPendingReturnRequest())
                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class="bi bi-clock-history"></i> Request Pengembalian Terkirim</h6>
                            <p class="mb-1">
                                Anda telah mengirim request pengembalian pada <strong>{{ $rental->return_requested_at->format('d F Y H:i') }}</strong>
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-hourglass-split"></i> Menunggu admin memverifikasi pembayaran dan mengembalikan planet Anda.
                            </p>
                        </div>
                    @elseif($rental->isOngoing() && $rental->calculateDaysLate() > 0)
                        <div class="alert alert-danger">
                            <h6 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Penyewaan Terlambat!</h6>
                            <p class="mb-0">
                                Anda terlambat <strong>{{ $rental->calculateDaysLate() }} hari</strong> dari tanggal pengembalian.
                                Denda yang harus dibayar: <strong>Rp {{ number_format($rental->calculateFine(), 0, ',', '.') }}</strong>
                            </p>
                        </div>
                    @elseif($rental->isOngoing())
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Penyewaan masih berjalan. Silakan kembalikan planet sebelum tanggal jatuh tempo.
                        </div>
                    @elseif($rental->isReturned() && $rental->fine > 0)
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> 
                            Unit telah dikembalikan dengan denda keterlambatan sebesar 
                            <strong>Rp {{ number_format($rental->fine, 0, ',', '.') }}</strong>
                        </div>
                    @else
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> 
                            Unit telah dikembalikan tepat waktu tanpa denda.
                        </div>
                    @endif

                    <!-- Button Kembalikan -->
                    @if($rental->isOngoing() && !$rental->hasPendingReturnRequest())
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#returnModal">
                                <i class="bi bi-box-arrow-in-down"></i> Request Pengembalian Planet
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Return dengan Upload Pembayaran -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white">
                    <i class="bi bi-box-arrow-in-down"></i> Request Pengembalian Planet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('member.rentals.return', $rental) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <!-- Info Pembayaran -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Total Pembayaran</h6>
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td>Biaya Sewa:</td>
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
                                <td class="text-end">
                                    Rp {{ number_format(($rental->unit->price_per_day * $rental->duration_days) + $rental->calculateFine(), 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Upload Bukti Pembayaran -->
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">
                            <i class="bi bi-image"></i> Upload Bukti Pembayaran <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" 
                               id="payment_proof" name="payment_proof" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, max 2MB</small>
                        @error('payment_proof')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Preview Image -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <label class="form-label">Preview:</label>
                        <img id="preview" src="" alt="Preview" class="img-fluid rounded border" style="max-height: 200px;">
                    </div>

                    <!-- Catatan -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan (Opsional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" 
                                  placeholder="Tambahkan catatan jika ada..."></textarea>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <small>
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Perhatian:</strong> Pastikan bukti pembayaran jelas dan sesuai dengan total yang harus dibayar. 
                            Admin akan memverifikasi pembayaran Anda sebelum mengembalikan planet.
                        </small>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-send"></i> Kirim Request Pengembalian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Preview image sebelum upload
document.getElementById('payment_proof').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>

@endsection
