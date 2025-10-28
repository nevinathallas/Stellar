<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $units = Unit::with('categories')
            ->when($search, function($query) use ($search) {
                return $query->search($search);
            })
            ->available()
            ->paginate(12);

        return view('home', compact('units', 'search'));
    }

    public function show(Unit $unit)
    {
        $unit->load('categories');
        return view('units.show', compact('unit'));
    }
}
