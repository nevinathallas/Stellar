<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Unit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    /**
     * Display user's rental list
     */
    public function index()
    {
        $user = auth()->user();
        
        $rentals = Rental::with('unit.categories')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.rentals.index', compact('rentals'));
    }

    /**
     * Show form to rent a unit
     */
    public function create(Unit $unit)
    {
        if (!$unit->isAvailable()) {
            return redirect()->route('home')
                ->with('error', 'Unit tidak tersedia untuk disewa!');
        }

        // Check if user already has 2 active rentals
        $activeRentalsCount = auth()->user()->activeRentals()->count();
        if ($activeRentalsCount >= 2) {
            return redirect()->route('home')
                ->with('error', 'Anda sudah mencapai batas maksimal 2 unit yang disewa!');
        }

        $unit->load('categories');
        return view('member.rentals.create', compact('unit'));
    }

    /**
     * Store a new rental
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'start_date' => 'required|date|after_or_equal:today',
            'duration_days' => 'required|integer|min:1|max:5',
        ]);

        // Check active rentals limit
        $activeRentalsCount = auth()->user()->activeRentals()->count();
        if ($activeRentalsCount >= 2) {
            return redirect()->route('home')
                ->with('error', 'Anda sudah mencapai batas maksimal 2 unit yang disewa!');
        }

        // Check if unit is still available
        $unit = Unit::findOrFail($validated['unit_id']);
        if (!$unit->isAvailable()) {
            return redirect()->route('home')
                ->with('error', 'Unit tidak tersedia untuk disewa!');
        }

        // Calculate end date
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addDays($validated['duration_days']);

        // Create rental
        Rental::create([
            'user_id' => auth()->id(),
            'unit_id' => $validated['unit_id'],
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration_days' => $validated['duration_days'],
            'status' => 'ongoing',
        ]);

        // Update unit status
        $unit->update(['status' => 'rented']);

        return redirect()->route('member.dashboard')
            ->with('success', 'Unit berhasil disewa! Silakan lakukan pembayaran.');
    }

    /**
     * Show rental detail
     */
    public function show(Rental $rental)
    {
        // Make sure user can only see their own rental
        if ($rental->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $rental->load('unit.categories');
        return view('member.rentals.show', compact('rental'));
    }
}
