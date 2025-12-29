<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Discount;

class HomeController extends Controller{
    public function index(){
        $branch = session('selected_branch');
        
        $featuredProducts = Product::where('is_available', true)
            ->when($branch, function($query) use ($branch) {
                return $query->where('branch_id', $branch->id);
            })
            ->inRandomOrder()
            ->take(8)
            ->get();
            
        $activeDiscount = Discount::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();

        return view('welcome', [
            'title' => 'Beranda',
            'featuredProducts' => $featuredProducts,
            'activeDiscount' => $activeDiscount
        ]);
    }
}