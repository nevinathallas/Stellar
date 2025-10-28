@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-4">
            <h2 class="fw-bold"><i class="bi bi-planet2"></i> Kelola Unit Planet</h2>
        </div>
        <div class="col-md-4">
            <form action="{{ route('admin.units.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari unit..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.units.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Unit
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            @if($units->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga/Hari</th>
                                <th>Status</th>
                                <th width="200" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($units as $unit)
                                <tr>
                                    <td>{{ $loop->iteration + ($units->currentPage() - 1) * $units->perPage() }}</td>
                                    <td><code>{{ $unit->code }}</code></td>
                                    <td><strong>{{ $unit->name }}</strong></td>
                                    <td>
                                        @foreach($unit->categories as $category)
                                            <span class="badge bg-secondary small">{{ $category->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}</td>
                                    <td>
                                        @if($unit->status === 'available')
                                            <span class="badge bg-success">Available</span>
                                        @else
                                            <span class="badge bg-danger">Rented</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.units.show', $unit) }}" 
                                               class="btn btn-sm btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.units.edit', $unit) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.units.destroy', $unit) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus unit ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $units->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Belum ada unit. 
                    <a href="{{ route('admin.units.create') }}" class="alert-link">Tambah unit baru</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
