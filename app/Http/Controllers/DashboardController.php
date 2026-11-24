<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\LaundryTransaction;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'customers' => Customer::count(),
            'transactions_today' => LaundryTransaction::whereDate('entry_date', today())->count(),
            'processing' => LaundryTransaction::whereIn('status', ['Diterima', 'Dicuci', 'Disetrika'])->count(),
            'ready' => LaundryTransaction::where('status', 'Siap Diambil')->count(),
            'revenue' => LaundryTransaction::where('payment_status', 'Lunas')
                ->whereMonth('entry_date', now()->month)
                ->whereYear('entry_date', now()->year)
                ->sum('total_amount'),
        ];

        $recentTransactions = LaundryTransaction::with(['customer', 'laundryService'])->latest()->limit(6)->get();

        $statusCounts = LaundryTransaction::toBase()
            ->selectRaw("SUM(CASE WHEN status = 'Diterima' THEN 1 ELSE 0 END) AS diterima")
            ->selectRaw("SUM(CASE WHEN status = 'Dicuci' THEN 1 ELSE 0 END) AS dicuci")
            ->selectRaw("SUM(CASE WHEN status = 'Disetrika' THEN 1 ELSE 0 END) AS disetrika")
            ->selectRaw("SUM(CASE WHEN status = 'Siap Diambil' THEN 1 ELSE 0 END) AS siap_diambil")
            ->selectRaw("SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) AS selesai")
            ->first();

        $monthExpression = match (DB::getDriverName()) {
            'sqlite' => "strftime('%Y-%m', entry_date)",
            'pgsql' => "to_char(entry_date, 'YYYY-MM')",
            default => "DATE_FORMAT(entry_date, '%Y-%m')",
        };

        $revenue = LaundryTransaction::selectRaw($monthExpression.' as month, SUM(total_amount) as total')
            ->where('payment_status', 'Lunas')
            ->groupBy('month')
            ->orderBy('month')
            ->limit(12)
            ->get();

        return view('dashboard', compact('stats', 'recentTransactions', 'revenue', 'statusCounts'));
    }
}
