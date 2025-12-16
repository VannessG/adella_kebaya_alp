<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Branch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ... [Method index (public), show (public) biarkan tetap ada] ...
    public function index(Request $request)
    {
        // ... (kode public index yang sudah ada) ...
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
        // ... (kode public show yang sudah ada) ...
        $branch = session('selected_branch');
        
        $product = Product::where('branch_id', $branch->id)
            ->findOrFail($id);
            
        return view('katalog.detail', [
            'title' => 'Detail ' . $product->name,
            'product' => $product,
            'category' => $product->category,
        ]);
    }

    // --- ADMIN METHODS ---

    // Pindahan dari AdminController::index (Produk)
    public function adminIndex()
    {
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

    // ... [Method create, store, edit, update, destroy biarkan seperti semula] ...
    public function create()
    {
        $categories = Category::all();
        $branches = Branch::where('is_active', true)->get();
        
        return view('admin.products.create', [
            'title' => 'Tambah Produk',
            'categories' => $categories,
            'branches' => $branches
        ]);
    }
    
    public function store(Request $request)
    {
        // ... (kode store yang sudah ada) ...
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'rent_price_per_day' => 'nullable|numeric|min:0',
            'min_rent_days' => 'nullable|integer|min:1',
            'max_rent_days' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'nullable|exists:branches,id',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'is_available_for_rent' => 'boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Jika branch tidak dipilih, gunakan branch dari session
        if (empty($validated['branch_id'])) {
            $branch = session('selected_branch');
            $validated['branch_id'] = $branch->id;
        }
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }
        
        $validated['is_available'] = $request->has('is_available');
        $validated['is_available_for_rent'] = $request->has('is_available_for_rent');
        
        Product::create($validated);
        
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }
    
    public function edit(Product $product)
    {
        $categories = Category::all();
        $branches = Branch::where('is_active', true)->get();
        
        return view('admin.products.edit', [
            'title' => 'Edit Produk',
            'product' => $product,
            'categories' => $categories,
            'branches' => $branches
        ]);
    }
    
    public function update(Request $request, Product $product)
    {
        // ... (kode update yang sudah ada) ...
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'rent_price_per_day' => 'nullable|numeric|min:0',
            'min_rent_days' => 'nullable|integer|min:1',
            'max_rent_days' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'nullable|exists:branches,id',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'is_available_for_rent' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Jika branch tidak dipilih, gunakan branch dari session
        if (empty($validated['branch_id'])) {
            $branch = session('selected_branch');
            $validated['branch_id'] = $branch->id;
        }
        
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        } else {
            $validated['image'] = $product->image;
        }
        
        $validated['is_available'] = $request->has('is_available');
        $validated['is_available_for_rent'] = $request->has('is_available_for_rent');
        
        $product->update($validated);
        
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }
    
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}