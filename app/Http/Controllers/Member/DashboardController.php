<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get user's active rentals
        $activeRentals = Rental::with('unit.categories')
            ->where('user_id', $user->id)
            ->ongoing()
            ->get();

        // Get user's rental history
        $rentalHistory = Rental::with('unit')
            ->where('user_id', $user->id)
            ->whereIn('status', ['returned', 'overdue'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('member.dashboard', compact('activeRentals', 'rentalHistory'));
    }
}
