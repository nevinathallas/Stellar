<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    /**
     * Display a listing of all rentals
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $rentals = Rental::with(['user', 'unit'])
            ->when($status, function($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.rentals.index', compact('rentals', 'status'));
    }

    /**
     * Display ongoing rentals
     */
    public function ongoing()
    {
        $rentals = Rental::with(['user', 'unit'])
            ->ongoing()
            ->orderBy('end_date', 'asc')
            ->get();

        return view('admin.rentals.ongoing', compact('rentals'));
    }

    /**
     * Display rental history (for printing)
     */
    public function history(Request $request)
    {
        $userId = $request->get('user_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        $rentals = Rental::with(['user', 'unit'])
            ->when($userId, function($query) use ($userId) {
                return $query->where('user_id', $userId);
            })
            ->when($startDate, function($query) use ($startDate) {
                return $query->whereDate('start_date', '>=', $startDate);
            })
            ->when($endDate, function($query) use ($endDate) {
                return $query->whereDate('end_date', '<=', $endDate);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.rentals.history', compact('rentals', 'userId', 'startDate', 'endDate'));
    }

    /**
     * Show the form for creating a new rental
     */
    public function create()
    {
        $units = Unit::available()->with('categories')->get();
        return view('admin.rentals.create', compact('units'));
    }

    /**
     * Display the specified rental
     */
    public function show(Rental $rental)
    {
        $rental->load(['user', 'unit.categories']);
        return view('admin.rentals.show', compact('rental'));
    }

    /**
     * Process return of rental unit
     */
    public function return(Request $request, Rental $rental)
    {
        if ($rental->isReturned()) {
            return redirect()->back()->with('error', 'Unit sudah dikembalikan!');
        }

        $returnedAt = Carbon::now();
        $fine = $rental->calculateFine();

        // Update rental
        $rental->update([
            'returned_at' => $returnedAt,
            'fine' => $fine,
            'status' => 'returned'
        ]);

        // Update unit status to available
        $rental->unit->update(['status' => 'available']);

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Unit berhasil dikembalikan! ' . 
                ($fine > 0 ? 'Denda: Rp ' . number_format($fine, 0, ',', '.') : 'Tidak ada denda.'));
    }

    /**
     * Update overdue status for rentals
     */
    public function updateOverdue()
    {
        $overdueRentals = Rental::ongoing()
            ->whereDate('end_date', '<', Carbon::today())
            ->get();

        foreach ($overdueRentals as $rental) {
            $rental->update([
                'status' => 'overdue',
                'fine' => $rental->calculateFine()
            ]);
        }

        return redirect()->back()
            ->with('success', 'Status keterlambatan berhasil diupdate!');
    }

    /**
     * Remove the specified rental from storage
     */
    public function destroy(Rental $rental)
    {
        // Only allow deletion of returned rentals
        if (!$rental->isReturned()) {
            return redirect()->back()
                ->with('error', 'Hanya rental yang sudah dikembalikan yang bisa dihapus!');
        }

        $rental->delete();

        return redirect()->route('admin.rentals.index')
            ->with('success', 'Data rental berhasil dihapus!');
    }
}
