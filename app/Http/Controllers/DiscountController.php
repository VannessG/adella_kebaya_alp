<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller{
    public function index(){
        // 1. Ambil data dengan pagination
        $discounts = Discount::latest()->paginate(10);

        // 2. Manipulasi data untuk kebutuhan View (Pindahkan logika PHP di sini)
        // Kita gunakan getCollection()->transform() karena data berbentuk Paginator
        $discounts->getCollection()->transform(function ($discount) {
            
            // Logika Status Aktif (Tanggal & Flag)
            $now = now();
            $isRealActive = $discount->is_active && $now->between($discount->start_date, $discount->end_date);
            
            // Menambahkan properti baru ke objek $discount khusus untuk View
            $discount->view_status_active = $isRealActive; // Boolean untuk warna badge
            $discount->view_status_label = $isRealActive ? 'Aktif' : 'Tidak Aktif'; // Teks Label
            
            // Logika Tipe Label (% atau IDR)
            $discount->view_type_label = $discount->type === 'percentage' ? '%' : 'IDR';
            
            // Logika Format Nilai (Persen atau Rupiah)
            $discount->view_amount_formatted = $discount->type === 'percentage'
                ? $discount->amount . '%'
                : 'Rp ' . number_format($discount->amount, 0, ',', '.');

            // Logika Format Periode
            $discount->view_period = $discount->start_date->format('d M Y') . ' - ' . $discount->end_date->format('d M Y');
            return $discount;
        });

        return view('admin.discounts.index', [
            'title' => 'Manajemen Diskon',
            'discounts' => $discounts
        ]);
    }
    
    public function checkCode(Request $request){
        $request->validate([
            'code' => 'required|string'
        ]);
        
        $discount = Discount::where('code', $request->code)
            ->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
        
        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Kode diskon tidak valid atau sudah kadaluarsa'
            ]);
        }
        
        if ($discount->max_usage && $discount->used_count >= $discount->max_usage) {
            return response()->json([
                'success' => false,
                'message' => 'Kode diskon sudah habis digunakan'
            ]);
        }
        
        return response()->json([
            'success' => true,
            'discount' => [
                'id' => $discount->id,
                'name' => $discount->name,
                'type' => $discount->type,
                'amount' => $discount->amount,
                'max_usage' => $discount->max_usage,
                'used_count' => $discount->used_count
            ]
        ]);
    }

    public function create(){
        return view('admin.discounts.create', [
            'title' => 'Tambah Diskon'
        ]);
    }
    
    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:discounts,code',
            'type' => 'required|in:percentage,fixed',
            'amount' => 'required|numeric|min:0',
            'max_usage' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);
        
        // Jika type percentage, amount maksimal 100
        if ($validated['type'] === 'percentage' && $validated['amount'] > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Diskon persentase maksimal 100%');
        }
        
        $validated['is_active'] = $request->has('is_active');
        $validated['used_count'] = 0;
        
        Discount::create($validated);
        
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil ditambahkan');
    }
    
    public function edit(Discount $discount){
        return view('admin.discounts.edit', [
            'title' => 'Edit Diskon',
            'discount' => $discount
        ]);
    }
    
    public function update(Request $request, Discount $discount){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:discounts,code,' . $discount->id,
            'type' => 'required|in:percentage,fixed',
            'amount' => 'required|numeric|min:0',
            'max_usage' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);
        
        if ($validated['type'] === 'percentage' && $validated['amount'] > 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Diskon persentase maksimal 100%');
        }
        
        $validated['is_active'] = $request->has('is_active');
        $discount->update($validated);
        
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil diperbarui');
    }
    
    public function destroy(Discount $discount){
        $discount->delete();
        
        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil dihapus');
    }
}