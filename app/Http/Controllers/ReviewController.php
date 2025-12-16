<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use App\Models\Rent;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        $orderId = session('review_order_id');
        $rentId = session('review_rent_id');
        
        if (!$orderId && !$rentId) {
            return redirect()->route('home')->with('error', 'Tidak ada pesanan untuk direview.');
        }
        
        $order = null;
        $rent = null;
        
        if ($orderId) {
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->first();
        }
        
        if ($rentId) {
            $rent = Rent::where('id', $rentId)
                ->where('user_id', Auth::id())
                ->where('status', 'completed')
                ->first();
        }
        
        if (!$order && !$rent) {
            return redirect()->route('home')->with('error', 'Pesanan tidak ditemukan.');
        }
        
        return view('reviews.create', [
            'title' => 'Beri Review',
            'order' => $order,
            'rent' => $rent
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'rent_id' => 'nullable|exists:rents,id',
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $userId = Auth::id();
        
        // Validasi: cek apakah sudah pernah review produk ini dalam transaksi yang sama
        if ($request->order_id) {
            if (Review::hasReviewed($userId, $request->product_id, $request->order_id)) {
                return redirect()->back()->with('error', 'Anda sudah memberikan review untuk produk ini dalam pesanan ini.');
            }
            
            $order = Order::where('id', $request->order_id)
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->first();
                
            if (!$order) {
                return redirect()->back()->with('error', 'Pesanan tidak ditemukan atau belum selesai.');
            }
            
            if (!$order->products->contains('id', $request->product_id)) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan dalam pesanan.');
            }
        }
        
        if ($request->rent_id) {
            if (Review::hasReviewed($userId, $request->product_id, null, $request->rent_id)) {
                return redirect()->back()->with('error', 'Anda sudah memberikan review untuk produk ini dalam sewa ini.');
            }
            
            $rent = Rent::where('id', $request->rent_id)
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->first();
                
            if (!$rent) {
                return redirect()->back()->with('error', 'Sewa tidak ditemukan atau belum selesai.');
            }
            
            if (!$rent->products->contains('id', $request->product_id)) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan dalam sewa.');
            }
        }
        
        // Validasi: hanya salah satu yang boleh diisi
        if ($request->order_id && $request->rent_id) {
            return redirect()->back()->with('error', 'Hanya boleh memilih satu jenis transaksi.');
        }
        
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }
        
        Review::create([
            'user_id' => $userId,
            'order_id' => $request->order_id,
            'rent_id' => $request->rent_id,
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'image' => $imagePath,
            'is_approved' => true
        ]);
        
        session()->forget(['review_order_id', 'review_rent_id']);
        
        return redirect()->route('reviews.index')
            ->with('success', 'Review berhasil dikirim. Terima kasih!');
    }
    
    public function index()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with(['product', 'order', 'rent'])
            ->latest()
            ->paginate(10);
            
        return view('reviews.index', [
            'title' => 'Review Saya',
            'reviews' => $reviews
        ]);
    }
    
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        if ($review->image && Storage::disk('public')->exists($review->image)) {
            Storage::disk('public')->delete($review->image);
        }
        
        $review->delete();
        
        return redirect()->route('reviews.index')
            ->with('success', 'Review berhasil dihapus');
    }

    public function adminIndex()
    {
        $reviews = Review::with(['user', 'product', 'order', 'rent'])
            ->latest()
            ->paginate(10);
            
        return view('admin.reviews.index', [
            'title' => 'Manajemen Review',
            'reviews' => $reviews
        ]);
    }
    
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        
        return redirect()->route('admin.reviews.index')->with('success', 'Review berhasil disetujui.');
    }
    
    public function destroyAdmin(Review $review)
    {
        $review->delete();
        
        return redirect()->route('admin.reviews.index')->with('success', 'Review berhasil dihapus.');
    }


}