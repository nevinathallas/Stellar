@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold"><i class="bi bi-people"></i> Kelola Users</h2>
            <p class="text-muted">Manage admin dan member</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah User
            </a>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.users.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter Role</label>
                        <select name="role" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>Member</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cari User</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nama atau email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Total Rentals</th>
                                <th>Terdaftar</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $index => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $user->name }}</strong>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge bg-danger">Admin</span>
                                        @else
                                            <span class="badge bg-primary">Member</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $user->rentals->count() }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $user->created_at->format('d M Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="btn btn-info" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="btn btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if($user->id != auth()->id())
                                                <form action="{{ route('admin.users.destroy', $user) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled title="Tidak bisa hapus diri sendiri">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Tidak ada user yang ditemukan.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
