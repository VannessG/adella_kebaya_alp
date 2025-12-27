<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Branch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller{
    public function index(Request $request, $categoryId = null){
        $branch = session('selected_branch');

        if (!$branch) {
            return redirect()->route('select.branch');
        }

        $query = Product::where('branch_id', $branch->id)->where('is_available', true);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhereHas('category', fn($cat) =>
                        $cat->where('name', 'like', "%$search%")
                    );
            });
        }

        return view('katalog.index', [
            'title' => 'Katalog Kebaya',
            'products' => $query->latest()->paginate(8),
            'categories' => Category::all(),
            'search' => $request->search,
            'currentCategoryId' => $categoryId
        ]);
    }

    public function show($id){
        $branch = session('selected_branch');
        $product = Product::where('branch_id', $branch->id)
            ->where('is_available', true)
            ->findOrFail($id);
            
        return view('katalog.detail', [
            'title' => 'Detail ' . $product->name,
            'product' => $product,
            'category' => $product->category,
        ]);
    }

    public function adminIndex(){
        $branch = session('selected_branch');
        $products = Product::with(['category', 'branch'])
            ->when($branch, function($q) use ($branch) {
                return $q->where('branch_id', $branch->id);
            })
            ->latest()
            ->paginate(10);
            
        return view('admin.products.index', [
            'title' => 'Manajemen Produk',
            'products' => $products
        ]);
    }

    public function create(){
        return view('admin.products.create', [
            'title' => 'Tambah Produk',
            'categories' => Category::all(),
            'branches' => Branch::all()
        ]);
    }
    
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1', 
            'rent_price_per_day' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id', 
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        $validated['is_available'] = $request->has('is_available');
        $validated['is_available_for_rent'] = $request->has('is_available_for_rent');
        Product::create($validated);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }
    
    public function edit(Product $product){
        return view('admin.products.edit', [
            'title' => 'Edit Produk',
            'product' => $product,
            'categories' => Category::all(),
            'branches' => Branch::all()
        ]);
    }
    
    public function update(Request $request, Product $product){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'weight' => 'required|integer|min:1',
            'rent_price_per_day' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'required|exists:branches,id',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);
        
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }
        $validated['is_available'] = $request->has('is_available');
        $validated['is_available_for_rent'] = $request->has('is_available_for_rent');
        $product->update($validated);
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }
    
    public function destroy(Product $product){
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}