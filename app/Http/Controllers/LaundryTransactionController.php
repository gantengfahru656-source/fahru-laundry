<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LaundryService;
use App\Models\LaundryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaundryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        $transactions = LaundryTransaction::with(['customer', 'laundryService'])
            ->when($search, function ($query) use ($search) {
                $query->where('transaction_code', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn ($customer) => $customer->where('name', 'like', "%{$search}%"));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('laundry.transactions.index', compact('transactions', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $services = LaundryService::orderBy('name')->get();

        return view('laundry.transactions.create', compact('customers', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'laundry_service_id' => ['required', 'exists:laundry_services,id'],
            'quantity' => ['required', 'numeric', 'min:0.1'],
            'payment_status' => ['required', 'in:Lunas,Belum Bayar'],
            'entry_date' => ['required', 'date'],
            'estimated_completion' => ['required', 'date', 'after_or_equal:entry_date'],
            'status' => ['required', 'in:Diterima,Dicuci,Disetrika,Siap Diambil,Selesai'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $service = LaundryService::findOrFail($validated['laundry_service_id']);
        $validated['transaction_code'] = 'LD-'.now()->format('Ymd').'-'.strtoupper(Str::random(4));
        $validated['price_per_kg'] = $service->price_per_kg;
        $validated['weight'] = $validated['quantity'];
        $validated['unit_type'] = $service->unit_type;
        $validated['price_per_kg'] = $service->price_per_unit ?? $service->price_per_kg;
        $validated['total_amount'] = (int) round($validated['quantity'] * $validated['price_per_kg']);
        LaundryTransaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi laundry berhasil dicatat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LaundryTransaction $laundryTransaction)
    {
        $laundryTransaction->load(['customer', 'laundryService']);

        return view('laundry.transactions.show', compact('laundryTransaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LaundryTransaction $laundryTransaction)
    {
        $customers = Customer::orderBy('name')->get();
        $services = LaundryService::orderBy('name')->get();

        return view('laundry.transactions.edit', compact('laundryTransaction', 'customers', 'services'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LaundryTransaction $laundryTransaction)
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'laundry_service_id' => ['required', 'exists:laundry_services,id'],
            'quantity' => ['required', 'numeric', 'min:0.1'],
            'payment_status' => ['required', 'in:Lunas,Belum Bayar'],
            'entry_date' => ['required', 'date'],
            'estimated_completion' => ['required', 'date', 'after_or_equal:entry_date'],
            'status' => ['required', 'in:Diterima,Dicuci,Disetrika,Siap Diambil,Selesai'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        $service = LaundryService::findOrFail($validated['laundry_service_id']);
        $validated['weight'] = $validated['quantity'];
        $validated['unit_type'] = $service->unit_type;
        $validated['price_per_kg'] = $service->price_per_unit ?? $service->price_per_kg;
        $validated['total_amount'] = (int) round($validated['quantity'] * $validated['price_per_kg']);
        $laundryTransaction->update($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaksi laundry berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LaundryTransaction $laundryTransaction)
    {
        $laundryTransaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function tracking(Request $request)
    {
        $transaction = null;
        $code = trim((string) $request->query('code', ''));

        if ($code !== '') {
            $transaction = LaundryTransaction::with(['customer', 'laundryService'])
                ->where('transaction_code', $code)
                ->first();
        }

        return view('laundry.tracking', compact('transaction', 'code'));
    }

    public function printReceipt(LaundryTransaction $laundryTransaction)
    {
        $laundryTransaction->load(['customer', 'laundryService']);

        return view('laundry.transactions.print', compact('laundryTransaction'));
    }

    public function report(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $transactions = LaundryTransaction::with(['customer', 'laundryService'])
            ->when($validated['start_date'] ?? null, fn ($query, $date) => $query->whereDate('entry_date', '>=', $date))
            ->when($validated['end_date'] ?? null, fn ($query, $date) => $query->whereDate('entry_date', '<=', $date))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $totals = LaundryTransaction::query()
            ->when($validated['start_date'] ?? null, fn ($query, $date) => $query->whereDate('entry_date', '>=', $date))
            ->when($validated['end_date'] ?? null, fn ($query, $date) => $query->whereDate('entry_date', '<=', $date));
        $revenue = (clone $totals)->where('payment_status', 'Lunas')->sum('total_amount');
        $outstanding = (clone $totals)->where('payment_status', 'Belum Bayar')->sum('total_amount');

        return view('laundry.report', compact('transactions', 'revenue', 'outstanding', 'validated'));
    }

    public function exportReport(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $filename = 'laporan-laundry-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($validated) {
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Kode Invoice', 'Tanggal', 'Pelanggan', 'Layanan', 'Jumlah', 'Unit', 'Total', 'Status Cucian', 'Pembayaran']);

            LaundryTransaction::with(['customer', 'laundryService'])
                ->when($validated['start_date'] ?? null, fn ($query, $date) => $query->whereDate('entry_date', '>=', $date))
                ->when($validated['end_date'] ?? null, fn ($query, $date) => $query->whereDate('entry_date', '<=', $date))
                ->latest()
                ->chunk(200, function ($transactions) use ($output) {
                    foreach ($transactions as $transaction) {
                        fputcsv($output, [
                            $transaction->transaction_code,
                            $transaction->entry_date->format('Y-m-d'),
                            $transaction->customer->name,
                            $transaction->laundryService->name,
                            $transaction->quantity,
                            $transaction->unit_type,
                            $transaction->total_amount,
                            $transaction->status,
                            $transaction->payment_status,
                        ]);
                    }
                });

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
