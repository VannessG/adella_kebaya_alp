<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Rent;
use App\Models\User;
use App\Models\Discount;

class AdminController extends Controller{
    public function dashboard(){
        $branch = session('selected_branch');
        
        $stats = [
            'total_products' => Product::when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })->count(),
            
            'total_categories' => Category::count(),
            
            'total_orders' => Order::when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })->count(),
            
            'total_rents' => Rent::when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })->count(),
            
            'total_users' => User::where('role', 'user')
                ->when($branch, function($q) use ($branch) {
                    return $q->where('branch_id', $branch->id);
                })->count(),
            
            'pending_orders' => Order::where('status', 'pending')
                ->when($branch, function($q) use ($branch) {
                    return $q->where('branch_id', $branch->id);
                })->count(),
            
            'active_discount' => Discount::where('is_active', true)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->orderBy('created_at', 'desc')
                ->first(),
        ];

        // Recent orders (max 5)
        $recentOrders = Order::with(['user', 'products'])
            ->when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })
            ->latest()
            ->take(5)
            ->get();
            
        // Recent rents (max 5)
        $recentRents = Rent::with(['user', 'products'])
            ->when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', [
            'title' => 'Dashboard Admin',
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'recentRents' => $recentRents,
            'branch' => $branch
        ]);
    }
}