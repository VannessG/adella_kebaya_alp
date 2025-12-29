<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller{
    public function index(Request $request){
        // 1. Default filter: Bulan ini jika tidak ada input
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();
        // 2. Ambil data Order (Penjualan)
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['processing', 'shipping', 'completed']) // Hanya yang valid/dibayar
            ->get()
            ->map(function ($item) {
                $item->type = 'Jual';
                return $item;
            });
        // 3. Ambil data Rent (Sewa)
        $rents = Rent::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['confirmed', 'active', 'returned', 'completed'])
            ->get()
            ->map(function ($item) {
                $item->type = 'Sewa';
                return $item;
            });
        // 4. Gabungkan dan urutkan berdasarkan tanggal terbaru
        $allTransactions = $orders->concat($rents)->sortByDesc('created_at');
        // 5. Hitung Statistik
        $stats = [
            'total_income' => $allTransactions->sum('total_amount'),
            'count_order' => $orders->count(),
            'count_rent' => $rents->count(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
        ];
        return view('admin.reports.index', compact('allTransactions', 'stats'));
    }
}