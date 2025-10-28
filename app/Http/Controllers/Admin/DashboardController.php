<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\User;
use App\Models\Rental;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUnits = Unit::count();
        $totalMembers = User::where('role', 'member')->count();
        $totalCategories = Category::count();
        $activeRentals = Rental::where('status', 'ongoing')->count();
        $overdueRentals = Rental::where('status', 'overdue')->count();
        
        $recentRentals = Rental::with(['user', 'unit'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUnits',
            'totalMembers',
            'totalCategories',
            'activeRentals',
            'overdueRentals',
            'recentRentals'
        ));
    }
}
