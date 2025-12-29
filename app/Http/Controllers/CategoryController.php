<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller {
    
    // Perbaikan pada method show
    public function show($id, Request $request) {
        $branch = session('selected_branch');
        
        // 1. Ambil Data Kategori Saat Ini
        $category = Category::findOrFail($id);

        // 2. Query Dasar: Branch + (Jual ATAU Sewa)
        // Perbaikan syntax error 'where()where' disini
        $query = Product::where('branch_id', $branch->id)
            ->where(function($q) {
                $q->where('is_available', true)
                  ->orWhere('is_available_for_rent', true);
            });

        // 3. Filter Wajib: Hanya produk dari kategori ini
        $query->where('category_id', $category->id);

        // 4. Logika Search (Jika user mencari dalam kategori ini)
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
            });
        }

        // 5. Return View (Gunakan katalog.index agar tampilan konsisten dengan halaman utama)
        // Atau jika Anda punya view khusus 'category.show', pastikan strukturnya mirip katalog.index
        return view('katalog.index', [ 
            'title'             => 'Kategori: ' . $category->name,
            'products'          => $query->latest()->paginate(8),
            'categories'        => Category::all(), // Agar list tombol kategori tetap muncul
            'currentCategoryId' => $category->id,   // KUNCI: Agar tombol kategori ini menjadi 'active'
            'search'            => $search          // Agar input search tidak hilang setelah enter
        ]);
    }

    public function adminIndex() {
        $categories = Category::withCount('products')->latest()->paginate(10);
        
        return view('admin.categories.index', [
            'title' => 'Kelola Kategori',
            'categories' => $categories,
        ]);
    }

    public function create() {
        return view('admin.categories.create', [
            'title' => 'Tambah Kategori',
        ]);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category) {
        return view('admin.categories.edit', [
            'title' => 'Edit Kategori',
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category) {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);
        
        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category) {
        // Hapus produk terkait terlebih dahulu (opsional, tergantung kebijakan)
        Product::where('category_id', $category->id)->delete();
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori dan semua produknya berhasil dihapus.');
    }
}