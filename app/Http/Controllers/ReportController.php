<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Rent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller{
    public function index(Request $request){
        // 1. Setup Tanggal
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfMonth();

        // 2. Cek Session Cabang
        $branchId = null;
        if (session()->has('selected_branch')) {
            $branchId = session('selected_branch')->id;
        } elseif (session()->has('branch_id')) {
            $branchId = session('branch_id');
        }

        // 3. Query Dasar untuk Order
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['processing', 'shipping', 'completed']);

        // Filter Cabang Order
        if ($branchId) {
            $ordersQuery->where('branch_id', $branchId);
        }

        $orders = $ordersQuery->get()->map(function ($item) {
            $item->type = 'Jual';
            return $item;
        });

        // 4. Query Dasar untuk Rent
        $rentsQuery = Rent::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['confirmed', 'active', 'returned', 'completed']);

        // Filter Cabang Rent
        if ($branchId) {
            $rentsQuery->where('branch_id', $branchId);
        }

        $rents = $rentsQuery->get()->map(function ($item) {
            $item->type = 'Sewa';
            return $item;
        });

        // 5. Gabungkan dan urutkan
        $allTransactions = $orders->concat($rents)->sortByDesc('created_at');

        // 6. Hitung Statistik
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