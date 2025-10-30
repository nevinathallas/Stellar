# 📚 Penjelasan Lengkap Project Stellar Rent (Website Sewa Planet)

## 🎯 Ringkasan Project
**Stellar Rent** adalah sistem manajemen penyewaan planet berbasis web yang dibuat untuk memenuhi ujian LSP Bidang Web Developer. Project ini menggunakan **Laravel 12**, **Bootstrap 5**, dan **MySQL** dengan fitur lengkap untuk admin dan member.

---

## 🚀 Tahap Pengembangan

### **1. Setup Awal (Inisialisasi Project)**

#### a. Install Laravel 12
```bash
composer create-project laravel/laravel SnapRent
cd SnapRent
```

#### b. Konfigurasi Database
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stellar
DB_USERNAME=root
DB_PASSWORD=
```

#### c. Install Laravel Breeze (Authentication)
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
php artisan migrate
```

**Hasil:** Sistem login/register sudah jadi dengan tampilan Blade template.

---

### **2. Perancangan Database (Migration)**

Migration adalah file PHP yang mendefinisikan struktur tabel database. Laravel menggunakan migration agar struktur database bisa di-versioning seperti kode.

#### Contoh Migration: **Tabel Units (Planet)**

File: `database/migrations/xxxx_create_units_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Method up() dijalankan saat migration dieksekusi
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id(); // Primary key auto increment
            $table->string('code')->unique(); // Kode unik planet
            $table->string('name'); // Nama planet
            $table->text('description')->nullable(); // Deskripsi
            $table->decimal('price_per_day', 10, 2); // Harga per hari
            $table->string('image_url')->nullable(); // URL gambar
            $table->enum('status', ['available', 'rented'])->default('available'); // Status
            $table->timestamps(); // created_at, updated_at
        });
    }

    // Method down() dijalankan saat rollback migration
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
```

**Penjelasan:**
- `$table->id()` = Kolom ID auto increment (Primary Key)
- `$table->string('code')->unique()` = Kolom text dengan constraint UNIQUE
- `$table->decimal('price_per_day', 10, 2)` = Angka desimal untuk harga (max 10 digit, 2 desimal)
- `$table->enum('status', [...])` = Pilihan terbatas (available atau rented)
- `$table->timestamps()` = Otomatis buat kolom created_at & updated_at

**Menjalankan Migration:**
```bash
php artisan migrate
```

**Tabel yang Dibuat:**
1. `users` - Data user (admin & member)
2. `categories` - Kategori planet
3. `units` - Data planet
4. `unit_categories` - Pivot table (many-to-many)
5. `rentals` - Data penyewaan

---

### **3. Membuat Model (Eloquent ORM)**

Model adalah class PHP yang merepresentasikan tabel database dan logic bisnisnya.

#### Contoh Model: **Unit (Planet)**

File: `app/Models/Unit.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    // Nama tabel (opsional, Laravel auto-detect dari nama model plural)
    protected $table = 'units';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'code',
        'name',
        'description',
        'price_per_day',
        'image_url',
        'status'
    ];

    // Casting tipe data
    protected $casts = [
        'price_per_day' => 'decimal:2',
    ];

    /**
     * Relasi Many-to-Many dengan Categories
     * Satu planet bisa punya banyak kategori
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'unit_categories');
    }

    /**
     * Relasi One-to-Many dengan Rentals
     * Satu planet bisa disewa berkali-kali (riwayat)
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

    /**
     * Scope Query: Ambil hanya planet yang available
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Method Helper: Cek apakah planet tersedia
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}
```

**Penjelasan:**
- **$fillable**: Kolom yang bisa diisi dengan `Unit::create([...])` (proteksi mass assignment)
- **$casts**: Konversi otomatis tipe data saat ambil dari database
- **Relasi belongsToMany**: Many-to-Many lewat pivot table `unit_categories`
- **Relasi hasMany**: One-to-Many (1 unit punya banyak rental)
- **Scope**: Query reusable `Unit::available()->get()`
- **Method Helper**: Function tambahan untuk logic bisnis

**Cara Menggunakan Model:**
```php
// Ambil semua planet tersedia
$planets = Unit::available()->get();

// Ambil planet dengan kategorinya
$planet = Unit::with('categories')->find(1);

// Cek status
if ($planet->isAvailable()) {
    echo "Bisa disewa!";
}
```

---

### **4. Membuat Seeder (Data Awal)**

Seeder adalah file PHP untuk mengisi data awal ke database (dummy data atau data master).

#### Contoh Seeder: **CategorySeeder**

File: `database/seeders/CategorySeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Planet Berbatu', 'description' => 'Planet dengan permukaan padat'],
            ['name' => 'Planet Gas', 'description' => 'Planet raksasa gas'],
            ['name' => 'Planet Es', 'description' => 'Planet dengan suhu sangat dingin'],
            ['name' => 'Planet Cincin', 'description' => 'Planet yang memiliki cincin'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ 4 kategori berhasil dibuat!');
    }
}
```

**Menjalankan Seeder:**
```bash
# Semua seeder
php artisan db:seed

# Seeder spesifik
php artisan db:seed --class=CategorySeeder
```

**Seeder yang Dibuat:**
1. `CategorySeeder` - 4 kategori planet
2. `UnitSeeder` - 10 planet dengan relasi kategori
3. `UserSeeder` - Admin & 2 member (John Doe, Jane Smith)
4. `OverdueRentalSeeder` - Data testing (Jane punya 2 sewa telat)

---

### **5. Membuat Controller (Logic Bisnis)**

Controller menangani request dari user dan mengembalikan response (view/redirect).

#### Contoh Controller: **UnitController (Admin)**

File: `app/Http/Controllers/Admin/UnitController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of units (READ)
     */
    public function index()
    {
        $units = Unit::with('categories')->paginate(10);
        return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit (CREATE - Form)
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.units.create', compact('categories'));
    }

    /**
     * Store a newly created unit (CREATE - Process)
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:units,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price_per_day' => 'required|numeric|min:0',
            'image_url' => 'nullable|url|max:500',
            'status' => 'required|in:available,rented',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        // Buat unit baru
        $unit = Unit::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price_per_day' => $validated['price_per_day'],
            'image_url' => $validated['image_url'] ?? null,
            'status' => $validated['status'],
        ]);

        // Attach categories (many-to-many)
        $unit->categories()->attach($validated['categories']);

        return redirect()->route('admin.units.index')
            ->with('success', 'Planet berhasil ditambahkan!');
    }

    /**
     * Display the specified unit (READ - Detail)
     */
    public function show(Unit $unit)
    {
        $unit->load('categories', 'rentals.user');
        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing (UPDATE - Form)
     */
    public function edit(Unit $unit)
    {
        $categories = Category::all();
        return view('admin.units.edit', compact('unit', 'categories'));
    }

    /**
     * Update the specified unit (UPDATE - Process)
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:units,code,' . $unit->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price_per_day' => 'required|numeric|min:0',
            'image_url' => 'nullable|url|max:500',
            'status' => 'required|in:available,rented',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        // Update unit
        $unit->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price_per_day' => $validated['price_per_day'],
            'image_url' => $validated['image_url'] ?? null,
            'status' => $validated['status'],
        ]);

        // Sync categories (replace semua)
        $unit->categories()->sync($validated['categories']);

        return redirect()->route('admin.units.index')
            ->with('success', 'Planet berhasil diupdate!');
    }

    /**
     * Remove the specified unit (DELETE)
     */
    public function destroy(Unit $unit)
    {
        // Cek apakah planet punya rental aktif
        if ($unit->rentals()->where('status', 'ongoing')->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak bisa hapus planet yang sedang disewa!');
        }

        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Planet berhasil dihapus!');
    }
}
```

**Penjelasan Method:**
1. **index()** - Tampilkan daftar planet (dengan pagination)
2. **create()** - Tampilkan form tambah planet
3. **store()** - Proses tambah planet (validasi → simpan → redirect)
4. **show()** - Tampilkan detail 1 planet
5. **edit()** - Tampilkan form edit planet
6. **update()** - Proses edit planet
7. **destroy()** - Hapus planet (dengan validasi)

**Route untuk Controller:**
```php
Route::resource('units', UnitController::class);
```

Otomatis generate 7 route (index, create, store, show, edit, update, destroy)

---

### **6. Membuat View (Tampilan Blade)**

View adalah file template Blade yang menampilkan HTML dinamis.

#### Contoh View: **List Planet (Admin)**

File: `resources/views/admin/units/index.blade.php`

```blade
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary d-flex justify-content-between">
            <h4 class="text-white mb-0">
                <i class="bi bi-planet"></i> Kelola Planet
            </h4>
            <a href="{{ route('admin.units.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> Tambah Planet
            </a>
        </div>
        <div class="card-body">
            {{-- Alert Success --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Tabel Planet --}}
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kode</th>
                            <th>Nama Planet</th>
                            <th>Kategori</th>
                            <th>Harga/Hari</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($units as $unit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><code>{{ $unit->code }}</code></td>
                            <td><strong>{{ $unit->name }}</strong></td>
                            <td>
                                @foreach($unit->categories as $category)
                                    <span class="badge bg-info">{{ $category->name }}</span>
                                @endforeach
                            </td>
                            <td>Rp {{ number_format($unit->price_per_day, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $unit->status == 'available' ? 'success' : 'danger' }}">
                                    {{ $unit->status == 'available' ? 'Tersedia' : 'Disewa' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.units.show', $unit) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" 
                                          onsubmit="return confirm('Yakin hapus planet ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data planet</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $units->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
```

**Penjelasan Blade Syntax:**
- `@extends('layouts.app')` - Pakai layout utama
- `@section('content')` - Isi konten ke placeholder
- `{{ $variable }}` - Echo variable (auto escape HTML)
- `@if, @foreach, @forelse` - Control structures
- `{{ route('admin.units.create') }}` - Generate URL dari route name
- `{{ $units->links() }}` - Tampilkan pagination Bootstrap

---

### **7. Routing (Penghubung URL ke Controller)**

File: `routes/web.php`

```php
<?php

use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Member\RentalController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Admin routes (butuh login + role admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('units', UnitController::class); // 7 route CRUD
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    
    // Custom routes
    Route::get('/rentals/ongoing', [RentalController::class, 'ongoing'])->name('rentals.ongoing');
    Route::put('/rentals/{rental}/return', [RentalController::class, 'return'])->name('rentals.return');
});

// Member routes (butuh login + role member)
Route::middleware(['auth', 'member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/create/{unit}', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::put('/rentals/{rental}/return', [RentalController::class, 'return'])->name('rentals.return');
});

require __DIR__.'/auth.php'; // Routes dari Breeze (login, register, logout)
```

**Penjelasan:**
- **middleware(['auth'])** - Butuh login
- **middleware(['auth', 'admin'])** - Butuh login + role admin
- **prefix('admin')** - URL jadi `/admin/...`
- **name('admin.')** - Route name jadi `admin.units.index`
- **Route::resource()** - Auto generate 7 route CRUD

---

### **8. Middleware (Proteksi Route)**

Middleware adalah filter yang dijalankan sebelum request masuk ke controller.

#### Contoh: AdminMiddleware

File: `app/Http/Middleware/AdminMiddleware.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user login dan role = admin
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request); // Lanjutkan ke controller
        }

        // Kalau bukan admin, redirect ke home
        return redirect()->route('home')->with('error', 'Akses ditolak! Hanya admin yang boleh.');
    }
}
```

**Registrasi Middleware:**

File: `bootstrap/app.php`

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        'member' => \App\Http\Middleware\MemberMiddleware::class,
    ]);
})
```

---

### **9. Fitur Utama yang Sudah Dibuat**

#### A. **Autentikasi (Laravel Breeze)**
- Login, Register, Logout
- Forgot Password (email reset)
- Role-based access (admin/member)

#### B. **Admin Panel (10 View CRUD)**
1. **Dashboard** - Statistik rental (ongoing, overdue, completed)
2. **Kelola Kategori** - CRUD kategori planet
3. **Kelola Planet** (4 view: index, create, show, edit)
4. **Kelola User** (4 view: index, create, show, edit)
5. **Kelola Rental** (3 view: index, ongoing, history)

#### C. **Member Panel**
1. **Dashboard** - List rental member
2. **Home** - Browse planet tersedia (search by name)
3. **Sewa Planet** - Form rental (max 5 hari, max 2 unit)
4. **Riwayat Sewa** - List rental member dengan filter status
5. **Request Pengembalian** - Upload bukti pembayaran + notes

#### D. **Fitur Business Logic**
- Validasi max 2 unit per member
- Validasi max 5 hari sewa
- Auto calculate denda (Rp 100k/hari)
- Status rental (ongoing, returned)
- Request pengembalian dari member → Admin verifikasi
- Pagination Bootstrap 5

---

### **10. Alur Proses Rental (End-to-End)**

```
1. MEMBER LOGIN
   ↓
2. BROWSE PLANET (Home page)
   - Search by name
   - Filter available only
   ↓
3. KLIK "SEWA" → FORM RENTAL
   - Pilih tanggal mulai (min: today)
   - Pilih durasi (1-5 hari)
   - System auto calculate end_date & total harga
   ↓
4. SUBMIT RENTAL
   - Validasi: max 2 ongoing rental
   - Create rental (status: ongoing)
   - Update unit (status: rented)
   ↓
5. MEMBER LIHAT DASHBOARD
   - Ada alert kalau ada sewa aktif
   - Klik detail rental → Lihat info lengkap
   ↓
6. MEMBER REQUEST PENGEMBALIAN
   - Upload bukti pembayaran (image)
   - Tulis notes (optional)
   - Submit → rental.return_requested_at = now()
   ↓
7. ADMIN LIHAT ONGOING RENTALS
   - Row dengan request = highlight kuning + badge "Ada Request"
   - Klik detail → Lihat bukti pembayaran + notes member
   ↓
8. ADMIN VERIFIKASI & KEMBALIKAN
   - Klik button "Kembalikan"
   - Lihat preview bukti pembayaran
   - Konfirmasi → System auto:
     * Calculate fine (jika telat)
     * Update rental (status: returned, returned_at: now(), fine: xxx)
     * Update unit (status: available)
     * Mark request as verified
   ↓
9. MEMBER CEK RIWAYAT
   - Status berubah jadi "Returned"
   - Lihat denda (jika ada)
```

---

### **11. Bug Fixes & Improvements**

#### Phase 1: NULL Pointer Bugs
**Masalah:** View error saat akses rental dengan NULL dates
```php
// BEFORE (Error)
{{ $rental->due_date->format('d/m/Y') }}

// AFTER (Fixed)
@if($rental->due_date)
    {{ $rental->due_date->format('d/m/Y') }}
@else
    <span class="text-muted">-</span>
@endif
```

#### Phase 2: CSS Optimization
- Sebelum: 1190 lines CSS
- Sesudah: 475 lines (60% reduction)
- Menggunakan Bootstrap utility classes

#### Phase 3: Pagination Fix
```php
// AppServiceProvider.php
use Illuminate\Pagination\Paginator;

public function boot(): void
{
    Paginator::useBootstrapFive(); // Fix pagination styling
}
```

#### Phase 4: Image Upload Bug
```php
// UnitController.php - Tambah image_url ke validation & create
'image_url' => 'nullable|url|max:500',
'image_url' => $validated['image_url'] ?? null,
```

#### Phase 5: Overdue Counting Fix
```php
// BEFORE (Salah)
$overdueRentals = Rental::where('status', 'overdue')->count();

// AFTER (Benar)
$overdueRentals = Rental::where('status', 'ongoing')
    ->whereNotNull('end_date')
    ->whereDate('end_date', '<', now())
    ->count();
```

#### Phase 6: Modal UI Fix
**Masalah:** Modal di dalam `<tbody>` jadi render sebagai table row
```html
<!-- BEFORE (Salah) -->
<tbody>
    @foreach($rentals as $rental)
    <tr>...</tr>
    <div class="modal">...</div> <!-- ❌ Di dalam tbody -->
    @endforeach
</tbody>

<!-- AFTER (Benar) -->
<tbody>
    @foreach($rentals as $rental)
    <tr>...</tr>
    @endforeach
</tbody>
@foreach($rentals as $rental)
<div class="modal">...</div> <!-- ✅ Di luar table -->
@endforeach
```

#### Phase 7: Decimal Days Fix
```php
// BEFORE (Decimal: 3.11 hari)
{{ $rental->due_date->diffInDays(now()) }}

// AFTER (Integer: 3 hari)
{{ (int) $rental->due_date->diffInDays(now()) }}
```

#### Phase 8: Alert Link Visibility (Dark Theme)
```html
<!-- BEFORE (Kurang terlihat) -->
<a href="#" class="alert-link">Lihat Semua</a>

<!-- AFTER (Jelas) -->
<a href="#" class="btn btn-primary">
    <i class="bi bi-list"></i> Lihat Semua
</a>
```

#### Phase 9: Return Request System
**Konsep:** Member tidak bisa langsung return, harus minta approval admin

**Database Changes:**
```php
// Migration: add_return_request_to_rentals_table
$table->string('payment_proof')->nullable(); // Path bukti bayar
$table->text('notes')->nullable(); // Catatan member
$table->timestamp('return_requested_at')->nullable(); // Waktu request
$table->boolean('return_request_status')->default(false); // false = pending, true = verified
```

**Alur:**
1. Member upload bukti bayar → `return_requested_at = now()`
2. Admin lihat request → Badge "Ada Request" di table
3. Admin klik detail → Preview bukti bayar + notes
4. Admin klik "Verifikasi & Kembalikan" → `return_request_status = true`

#### Phase 10: Late Display Format
```php
// Tampilkan dalam jam jika < 24 jam, hari jika >= 24 jam
@php
    $diffInHours = $rental->due_date->diffInHours(now());
    if ($diffInHours < 24) {
        echo $diffInHours . ' jam terlambat';
    } else {
        $diffInDays = floor($diffInHours / 24);
        echo $diffInDays . ' hari terlambat';
    }
@endphp
```

---

### **12. Testing Data (Seeder)**

#### OverdueRentalSeeder
```php
// Buat 2 rental telat untuk Jane Smith (member)
Rental::create([
    'user_id' => $jane->id,
    'unit_id' => $mercury->id, // Planet Mercury
    'start_date' => now()->subDays(8), // 8 hari lalu
    'end_date' => now()->subDays(3), // Jatuh tempo 3 hari lalu (TELAT)
    'duration_days' => 5,
    'status' => 'ongoing',
]);
```

Hasil:
- Jane punya 2 sewa telat (Mercury 3 hari, Venus 7 hari)
- Dashboard admin tampil "3 Overdue"
- Ongoing page tampil row merah + denda

---

### **13. Tech Stack Summary**

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 12 |
| Frontend | Blade Template, Bootstrap 5.3.0 |
| Icons | Bootstrap Icons |
| Database | MySQL (stellar) |
| Authentication | Laravel Breeze |
| Server | Laravel Built-in / Laragon |
| Version Control | Git (GitHub) |

---

### **14. File Structure Penting**

```
SnapRent/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Controller admin
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UnitController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── RentalController.php
│   │   │   └── Member/          # Controller member
│   │   │       ├── DashboardController.php
│   │   │       └── RentalController.php
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       └── MemberMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Unit.php
│       └── Rental.php
├── database/
│   ├── migrations/              # Struktur database
│   │   ├── xxxx_create_users_table.php
│   │   ├── xxxx_create_categories_table.php
│   │   ├── xxxx_create_units_table.php
│   │   ├── xxxx_create_unit_categories_table.php
│   │   ├── xxxx_create_rentals_table.php
│   │   ├── xxxx_add_payment_proof_to_rentals_table.php
│   │   └── xxxx_add_return_request_to_rentals_table.php
│   └── seeders/
│       ├── CategorySeeder.php
│       ├── UnitSeeder.php
│       ├── UserSeeder.php
│       └── OverdueRentalSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php   # Layout utama
│   │   ├── admin/              # View admin
│   │   │   ├── dashboard.blade.php
│   │   │   ├── categories/
│   │   │   ├── units/
│   │   │   ├── users/
│   │   │   └── rentals/
│   │   ├── member/             # View member
│   │   │   ├── dashboard.blade.php
│   │   │   └── rentals/
│   │   ├── home.blade.php      # Public home
│   │   └── welcome.blade.php
│   └── css/
│       └── app.css             # Custom CSS (475 lines)
├── routes/
│   └── web.php                 # Routing
├── public/
│   ├── css/
│   │   └── app.css
│   └── storage/                # Symlink ke storage/app/public
│       └── payment_proofs/     # Upload bukti bayar
├── storage/
│   └── app/
│       └── public/
│           └── payment_proofs/
├── .env                        # Config database
├── composer.json               # Dependencies PHP
└── README_SEWA_PLANET_LSP.md   # Dokumentasi resmi LSP
```

---

### **15. Command Laravel Penting**

```bash
# Migration
php artisan migrate              # Jalankan semua migration
php artisan migrate:rollback     # Rollback migration terakhir
php artisan migrate:fresh        # Drop all tables & re-migrate
php artisan migrate:fresh --seed # + Jalankan seeder

# Seeder
php artisan db:seed              # Jalankan semua seeder
php artisan db:seed --class=CategorySeeder  # Seeder spesifik

# Generate Files
php artisan make:model Unit -m   # Model + Migration
php artisan make:controller Admin/UnitController --resource  # Controller CRUD
php artisan make:migration create_units_table
php artisan make:seeder UnitSeeder
php artisan make:middleware AdminMiddleware

# Storage
php artisan storage:link         # Buat symlink public/storage

# Cache
php artisan route:clear          # Clear route cache
php artisan config:clear         # Clear config cache
php artisan view:clear           # Clear compiled views

# Development
php artisan serve                # Jalankan server (localhost:8000)
php artisan tinker               # Interactive console
```

---

### **16. Kesimpulan**

Project **Stellar Rent** adalah sistem penyewaan planet yang lengkap dengan:

✅ **10 Admin Views** (Dashboard + CRUD Categories, Units, Users, Rentals)  
✅ **5 Member Views** (Dashboard, Home, Browse, Rental Form, History)  
✅ **Authentication & Authorization** (Breeze + Role-based middleware)  
✅ **Business Logic** (Max 2 units, Max 5 days, Auto calculate fine)  
✅ **Return Request System** (Member → Upload proof → Admin verify)  
✅ **Optimized CSS** (60% reduction menggunakan Bootstrap utilities)  
✅ **Bug-free** (NULL checks, pagination, modal placement, decimal fix)  
✅ **Testing Data** (Seeder untuk development & demo)  

**Pattern MVC yang digunakan:**
- **Model**: Eloquent ORM untuk database interaction
- **View**: Blade template untuk UI
- **Controller**: Business logic & request handling
- **Migration**: Database versioning
- **Seeder**: Dummy data untuk testing

Project ini mendemonstrasikan pemahaman penuh tentang Laravel ecosystem, relational database, dan best practices web development! 🚀
