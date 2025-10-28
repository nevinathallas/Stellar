<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Category;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $units = Unit::with('categories')
            ->when($search, function($query) use ($search) {
                return $query->search($search);
            })
            ->paginate(10);

        return view('admin.units.index', compact('units', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.units.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:units,code',
            'name' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:available,rented',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        $unit = Unit::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'price_per_day' => $validated['price_per_day'],
            'status' => $validated['status'],
        ]);

        $unit->categories()->attach($validated['categories']);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit planet berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        $unit->load(['categories', 'rentals.user']);
        return view('admin.units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        $categories = Category::all();
        return view('admin.units.edit', compact('unit', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:units,code,' . $unit->id,
            'name' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:available,rented',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        $unit->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'price_per_day' => $validated['price_per_day'],
            'status' => $validated['status'],
        ]);

        $unit->categories()->sync($validated['categories']);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit planet berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit planet berhasil dihapus!');
    }
}
