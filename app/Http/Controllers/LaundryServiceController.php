<?php

namespace App\Http\Controllers;

use App\Models\LaundryService;
use Illuminate\Http\Request;

class LaundryServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = LaundryService::latest()->paginate(10);

        return view('laundry.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('laundry.services.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:laundry_services,name'],
            'price_per_kg' => ['nullable', 'integer', 'min:1'],
            'unit_type' => ['required', 'in:kg,pcs'],
            'price_per_unit' => ['required', 'integer', 'min:1'],
            'estimated_time' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        $validated['price_per_unit'] = $validated['price_per_unit'] ?? $validated['price_per_kg'];
        $validated['price_per_kg'] = $validated['price_per_unit'];
        LaundryService::create($validated);

        return redirect()->route('services.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LaundryService $laundryService)
    {
        return view('laundry.services.show', compact('laundryService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaundryService $laundryService)
    {
        return view('laundry.services.edit', compact('laundryService'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaundryService $laundryService)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:laundry_services,name,'.$laundryService->id],
            'price_per_kg' => ['nullable', 'integer', 'min:1'],
            'unit_type' => ['required', 'in:kg,pcs'],
            'price_per_unit' => ['required', 'integer', 'min:1'],
            'estimated_time' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
        $validated['price_per_unit'] = $validated['price_per_unit'] ?? $validated['price_per_kg'];
        $validated['price_per_kg'] = $validated['price_per_unit'];
        $laundryService->update($validated);

        return redirect()->route('services.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaundryService $laundryService)
    {
        if ($laundryService->transactions()->exists()) {
            return back()->with('error', 'Layanan tidak dapat dihapus karena masih digunakan oleh transaksi.');
        }
        $laundryService->delete();

        return redirect()->route('services.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
