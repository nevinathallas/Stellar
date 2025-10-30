<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use App\Models\Rental;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OverdueRentalSeeder extends Seeder
{
    /**
     * Seed rental yang telat untuk testing
     */
    public function run(): void
    {
        // Cari user Jane
        $jane = User::where('email', 'jane@example.com')->first();
        
        if (!$jane) {
            echo "User Jane tidak ditemukan. Jalankan DatabaseSeeder dulu.\n";
            return;
        }

        // Cari planet available untuk disewa
        $planets = Unit::where('status', 'available')->limit(2)->get();

        if ($planets->count() < 2) {
            echo "Tidak cukup planet available. Minimal butuh 2 planet.\n";
            return;
        }

        // RENTAL 1: Telat 3 hari (sudah lewat due date)
        $rental1 = Rental::create([
            'user_id' => $jane->id,
            'unit_id' => $planets[0]->id,
            'start_date' => Carbon::now()->subDays(8), // Mulai 8 hari yang lalu
            'end_date' => Carbon::now()->subDays(3),   // Seharusnya kembali 3 hari lalu
            'duration_days' => 5,
            'status' => 'ongoing', // Masih ongoing tapi sudah telat
            'fine' => 0, // Belum bayar denda
        ]);

        // Update status planet jadi rented
        $planets[0]->update(['status' => 'rented']);

        echo "✅ Rental telat 3 hari untuk Jane berhasil dibuat!\n";
        echo "   Planet: {$planets[0]->name}\n";
        echo "   Tanggal Sewa: {$rental1->start_date->format('d/m/Y')}\n";
        echo "   Jatuh Tempo: {$rental1->end_date->format('d/m/Y')}\n";
        echo "   Telat: 3 hari\n";
        echo "   Denda: Rp 300,000\n\n";

        // RENTAL 2: Telat 7 hari (lebih parah)
        $rental2 = Rental::create([
            'user_id' => $jane->id,
            'unit_id' => $planets[1]->id,
            'start_date' => Carbon::now()->subDays(12), // Mulai 12 hari yang lalu
            'end_date' => Carbon::now()->subDays(7),    // Seharusnya kembali 7 hari lalu
            'duration_days' => 5,
            'status' => 'ongoing', // Masih ongoing tapi sudah telat parah
            'fine' => 0, // Belum bayar denda
        ]);

        // Update status planet jadi rented
        $planets[1]->update(['status' => 'rented']);

        echo "✅ Rental telat 7 hari untuk Jane berhasil dibuat!\n";
        echo "   Planet: {$planets[1]->name}\n";
        echo "   Tanggal Sewa: {$rental2->start_date->format('d/m/Y')}\n";
        echo "   Jatuh Tempo: {$rental2->end_date->format('d/m/Y')}\n";
        echo "   Telat: 7 hari\n";
        echo "   Denda: Rp 700,000\n\n";

        echo "🎯 Total: 2 rental telat untuk testing\n";
        echo "📧 Login dengan: jane@example.com / password\n";
    }
}
