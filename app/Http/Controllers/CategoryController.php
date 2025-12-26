<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    // ==========================================
    // USER METHODS (Tampilan Katalog)
    // ==========================================

    public function index() {
        if (!session()->has('selected_branch')) {
            return redirect()->route('select.branch');
        }
        // Tampilan untuk USER (Katalog Kotak-kotak)
        return view('category.index', [
            'title' => 'Kategori Kebaya',
            'categories' => Category::all(),
        ]);
    }

    public function show($id) {
        $branch = session('selected_branch');
        $category = Category::findOrFail($id);
        
        $products = Product::where('category_id', $category->id)
            ->where('branch_id', $branch->id)
            ->where('is_available', true)
            ->get();
            
        return view('category.show', [
            'title' => $category->name,
            'category' => $category,
            'products' => $products,
        ]);
    }

    // ==========================================
    // ADMIN METHODS (Tampilan Tabel CRUD)
    // ==========================================

    // SESUAIKAN DENGAN web.php: Ganti nama dari adminIndex ke indexAdmin 
    // atau ubah route di web.php agar mengarah ke adminIndex
    public function adminIndex() {
        $categories = Category::withCount('products')->latest()->paginate(10);
        
        // PASTIKAN MENGARAH KE FOLDER admin.categories.index
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
        // Hapus produk terkait agar tidak terjadi error relasi
        Product::where('category_id', $category->id)->delete();
        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Kategori dan semua produknya berhasil dihapus.');
    }
}