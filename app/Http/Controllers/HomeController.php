<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // Middleware sudah handle cek cabang
        
        $branch = session('selected_branch');
        
        $featuredProducts = Product::where('is_available', true)
            ->when($branch, function($query) use ($branch) {
                return $query->where('branch_id', $branch->id);
            })
            ->inRandomOrder()
            ->take(8)
            ->get();
            
        return view('welcome', [
            'title' => 'Beranda',
            'featuredProducts' => $featuredProducts
        ]);
    }

    public function about()
    {
        // Middleware sudah handle cek cabang
        
        return view('about', [
            'title' => 'Tentang Kami'
        ]);
    }
}