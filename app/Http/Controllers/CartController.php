<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller{
    public function index(){
        $cart = session()->get('cart', []);
        $cartItems = [];
        $totalPrice = 0;
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $cartItems[] = [
                    'id' => $product->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'image_url' => $product->image_url,
                    'quantity' => $item['quantity'],
                    'subtotal' => $product->price * $item['quantity']
                ];
                $totalPrice += $product->price * $item['quantity'];
            }
        }
        $isEmpty = empty($cartItems);
        return view('cart.index', [
            'title' => 'Keranjang',
            'cartItems' => $cartItems,
            'isEmpty' => $isEmpty,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function add(Product $product, Request $request){
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'quantity' => $quantity,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image
            ];
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, $productId){
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove($productId){
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}