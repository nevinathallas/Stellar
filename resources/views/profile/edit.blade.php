@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <!-- Profile Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary">
                    <h4 class="mb-0 text-white"><i class="bi bi-person-circle"></i> Edit Profile</h4>
                </div>
                <div class="card-body p-4">
                    @if (session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>Profile berhasil diperbarui!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Password Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0 text-white"><i class="bi bi-key"></i> Ubah Password</h5>
                </div>
                <div class="card-body p-4">
                    @if (session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>Password berhasil diubah!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimal 8 karakter</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="card shadow border-danger">
                <div class="card-header bg-danger">
                    <h5 class="mb-0 text-white"><i class="bi bi-exclamation-triangle"></i> Hapus Akun</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-danger mb-3">
                        <strong>Peringatan:</strong> Tindakan ini tidak bisa dibatalkan!
                    </p>
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash"></i> Hapus Akun Saya
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Konfirmasi Hapus Akun</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Yakin ingin menghapus akun? Semua data akan hilang permanen.</p>
                    <div class="mb-3">
                        <label for="password" class="form-label">Masukkan Password:</label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
