<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $customers = Customer::query()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('laundry.customers.index', compact('customers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('laundry.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:customers,phone'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);
        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        $customer->load(['transactions.laundryService']);

        return view('laundry.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('laundry.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:20', 'unique:customers,phone,'.$customer->id],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);
        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        if ($customer->transactions()->exists()) {
            return back()->with('error', 'Pelanggan tidak dapat dihapus karena memiliki riwayat transaksi.');
        }
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
