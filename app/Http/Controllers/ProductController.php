<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Branch;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ==========================================
    // PUBLIC METHODS (Front End Katalog)
    // ==========================================

    public function index(Request $request)
    {
        // Middleware sudah handle cek cabang, ambil dari session
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
        $branch = session('selected_branch');
        
        // Pastikan produk milik cabang yang dipilih
        $product = Product::where('branch_id', $branch->id)
            ->findOrFail($id);
            
        return view('katalog.detail', [
            'title' => 'Detail ' . $product->name,
            'product' => $product,
            'category' => $product->category,
        ]);
    }

    // ==========================================
    // ADMIN METHODS (Manajemen Produk)
    // ==========================================

    public function adminIndex()
    {
        $branch = session('selected_branch');
        
        // Tampilkan produk sesuai cabang admin saat ini
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
        // --- 1. VALIDASI DATA MASUK ---
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            
            // [FIX] WAJIB ADA: Validasi Berat (Gram)
            'weight' => 'required|integer|min:1', 
            
            'rent_price_per_day' => 'nullable|numeric|min:0',
            'min_rent_days' => 'nullable|integer|min:1',
            'max_rent_days' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'nullable|exists:branches,id',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean', // Checkbox biasanya tidak terkirim jika unchecked
            'is_available_for_rent' => 'boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        // Jika branch tidak dipilih di form, gunakan branch dari session admin
        if (empty($validated['branch_id'])) {
            $branch = session('selected_branch');
            $validated['branch_id'] = $branch ? $branch->id : null;
        }
        
        // Upload Gambar
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }
        
        // Handle Checkbox (Convert null to false/0)
        $validated['is_available'] = $request->has('is_available');
        $validated['is_available_for_rent'] = $request->has('is_available_for_rent');
        
        // --- 2. SIMPAN KE DATABASE ---
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
        // --- 1. VALIDASI UPDATE ---
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            
            // [FIX] WAJIB ADA: Validasi Berat (Gram) saat update
            'weight' => 'required|integer|min:1',
            
            'rent_price_per_day' => 'nullable|numeric|min:0',
            'min_rent_days' => 'nullable|integer|min:1',
            'max_rent_days' => 'nullable|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'nullable|exists:branches,id',
            'description' => 'required|string',
            'stock' => 'required|integer|min:0',
            // Image nullable saat update (tidak wajib ganti gambar)
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' 
        ]);
        
        // Handle Branch
        if (empty($validated['branch_id'])) {
            $branch = session('selected_branch');
            $validated['branch_id'] = $branch ? $branch->id : $product->branch_id;
        }
        
        // Handle Upload Gambar Baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        } else {
            // Jika tidak upload baru, pakai gambar lama (hapus key image agar tidak null/tertimpa)
            unset($validated['image']);
        }
        
        // Handle Checkbox
        $validated['is_available'] = $request->has('is_available');
        $validated['is_available_for_rent'] = $request->has('is_available_for_rent');
        
        // --- 2. UPDATE DATABASE ---
        $product->update($validated);
        
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }
    
    public function destroy(Product $product)
    {
        // Hapus file gambar fisik
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}