<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    public function select()
    {
        $branches = Branch::where('is_active', true)->get();
        
        // Jika hanya ada 1 cabang, langsung pilih
        if ($branches->count() === 1) {
            session(['selected_branch' => $branches->first()]);
            return redirect()->route('home');
        }
        
        return view('branch.select', [
            'title' => 'Pilih Cabang',
            'branches' => $branches
        ]);
    }
    
    public function change(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);
        
        $branch = Branch::findOrFail($request->branch_id);
        session(['selected_branch' => $branch]);
        return redirect()->back();
    }

    public function storeSelection(Request $request){
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);
        $branch = Branch::findOrFail($request->branch_id);
        session(['selected_branch' => $branch]);
        return redirect()->route('home');
    }
    
    // Admin functions
    public function index(){
        $branches = Branch::latest()->paginate(10);
        return view('admin.branches.index', [
            'title' => 'Manajemen Cabang',
            'branches' => $branches
        ]);
    }
    
    public function create(){
        return view('admin.branches.create', [
            'title' => 'Tambah Cabang'
        ]);
    }
    
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);
        $validated['is_active'] = $request->has('is_active');
        Branch::create($validated);
        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang berhasil ditambahkan');
    }
    
    public function edit(Branch $branch){
        return view('admin.branches.edit', [
            'title' => 'Edit Cabang',
            'branch' => $branch
        ]);
    }
    
    public function update(Request $request, Branch $branch){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_active' => 'boolean'
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $branch->update($validated);
        
        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang berhasil diperbarui');
    }
    
    public function destroy(Branch $branch)
    {
        // Cek apakah ada produk atau order yang menggunakan cabang ini
        if ($branch->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus cabang yang memiliki produk');
        }
        
        $branch->delete();
        
        return redirect()->route('admin.branches.index')
            ->with('success', 'Cabang berhasil dihapus');
    }
}