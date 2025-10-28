@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-3">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="bi bi-tag"></i> Detail Kategori</h4>
                </div>
                <div class="card-body p-4">
                    <h3 class="mb-4">{{ $category->name }}</h3>

                    <div class="mb-3">
                        <strong>Jumlah Unit:</strong> <span class="badge bg-primary">{{ $category->units->count() }} unit</span>
                    </div>

                    <hr>

                    <h5 class="mb-3">Unit dalam Kategori Ini:</h5>
                    @if($category->units->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Planet</th>
                                        <th>Harga/Hari</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->units as $unit)
                                        <tr>
                                            <td><code>{{ $unit->code }}</code></td>
                                            <td>{{ $unit->name }}</td>
                                            <td>Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}</td>
                                            <td>
                                                @if($unit->status === 'available')
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-danger">Rented</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Belum ada unit dalam kategori ini.</p>
                    @endif

                    <hr>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                              onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
