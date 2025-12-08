<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Middleware sudah handle cek cabang
        $branch = session('selected_branch');
        
        if ($request->has('search')) {
            $search = $request->search;

            $products = Product::where('branch_id', $branch->id)
                ->where(function($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('description', 'like', "%$search%")
                        ->orWhereHas('category', fn($q) =>
                            $q->where('name', 'like', "%$search%")
                        );
                })
                ->paginate(8);
        } else {
            $products = Product::where('branch_id', $branch->id)->paginate(8);
        }

        return view('katalog.index', [
            'title' => 'Katalog Kebaya',
            'products' => $products,
            'categories' => Category::all(),
        ]);
    }

    public function show($id)
    {
        // Middleware sudah handle cek cabang
        $branch = session('selected_branch');
        
        $product = Product::where('branch_id', $branch->id)
            ->findOrFail($id);
            
        return view('katalog.detail', [
            'title' => 'Detail ' . $product->name,
            'product' => $product,
            'category' => $product->category,
        ]);
    }
}