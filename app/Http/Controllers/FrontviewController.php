<?php

namespace App\Http\Controllers;

use App\Models\banner;
use App\Models\Cart;
use App\Models\category;
use App\Models\service;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Http\Request;

class FrontviewController extends Controller
{
    public function index()
    {
        $userEmail = session('user');
           
    
        // Ambil model pengguna berdasarkan email dari session
        $user = User::where('email', $userEmail)->first();
        $banner = banner::all();
        $categories = category::all();
        $allServices = service::all();
        $services = service::all();
        $viewCategory = category::with('services')->get();

      if($userEmail){
        $cart = Cart::where('user_id', $user->id)->get();
      }else{
        $cart = collect();
      }
    
        // Kirim data ke view
        return view('front.view.index', compact('banner', 'categories', 'services', 'viewCategory', 'user', 'cart', 'allServices'));
    }
    public function login()
    {
        return redirect()->to('http://127.0.0.1:8000/auth/login');
    }
    
    

    public function logout()
    {
        session()->flush();
        Filament::auth()->logout();
      
       return redirect()->route('front.index');
    }
   
    public function detailServices($id)
    {

        session()->put('service_id', $id);
        $userEmail = session('user');
        $user = User::where('email', $userEmail)->first();
        $allServices = service::all();
        $services = service::find($id);
        $banner = banner::all();
        $categories = category::all();
        $variants = $services->variant;
        if($userEmail){
            $cart = Cart::where('user_id', $user->id)->get();
          }else{
            $cart = collect();
          }
        return view('front.view.detail', compact('services', 'banner', 'categories', 'variants', 'allServices', 'user', 'cart' ));
    }

    public function addToCart(Request $request)
    {
        $lastServiceId = $request->service_id;  // Menggunakan ID layanan yang diterima dari request
        $user_id = $request->user_id;
        $service_id = $request->service_id;
        $qty = $request->quantity;  // Pastikan nama parameter yang diterima benar, 'quantity' bukan 'qty'
        $variant = $request->variant;
        $price_item = $request->price_item;
        $total_price = $qty * $price_item;  // Menghitung total harga berdasarkan qty dan harga satuan
    
        // Cek apakah item dengan user_id dan variant yang sama sudah ada di database
        $existingCart = Cart::where('user_id', $user_id)
                            ->where('variant', $variant)
                            ->first();
    
        if ($existingCart) {
            // Jika item sudah ada, tambahkan qty dan total_price
            $existingCart->qty += $qty;
            $existingCart->total_price += $total_price;
            $existingCart->save();
        } else {
            // Jika item belum ada, buat item baru
            Cart::create([
                'user_id' => $user_id,
                'service_id' => $service_id,
                'qty' => $qty,
                'variant' => $variant,
                'total_price' => $total_price,
                'price_item' => $price_item,
            ]);
        }
    
        // return redirect()->route('front.detailServices', ['id' => $lastServiceId]);
    }
    function realtimeData(){
        $userEmail = session('user');
        $user = User::where('email', $userEmail)->first();
        $cart = Cart::where('user_id', $user->id)->get();
        return response()->json($cart);
    }

    function viewCart(){
        $userEmail = session('user');
        $user = User::where('email', $userEmail)->first();
        $id = $user->id;
        $allServices = service::all();
        $services = service::find($id);
        $banner = banner::all();
        $categories = category::all();
        if($userEmail){
            $cart = Cart::where('user_id', $user->id)->get();
          }else{
            $cart = collect();
          }
        return view('front.view.cart', compact('cart', 'user', 'allServices', 'services', 'banner', 'categories'));
    }
public function updateCart(Request $request) {
    $id = $request->id;
    $qty = $request->qty;
    
    // Temukan item di keranjang berdasarkan ID
    $cart = Cart::find($id);
    
    if ($cart) {
        // Update qty di keranjang
        $cart->qty = $qty;
        
        // Hitung ulang total_price berdasarkan price_item dan qty yang baru
        $cart->total_price = $cart->price_item * $qty;
        
        // Simpan perubahan
        $cart->save();
    }
}

    function realtimeCart(){
        $userEmail = session('user');
        $user = User::where('email', $userEmail)->first();
        $cart = Cart::where('user_id', $user->id)->get();
        return response()->json($cart);
    }
    public function handleUpload(Request $request)
{
    // Validasi file upload
    $request->validate([
        'file' => 'required|file|mimes:jpg,png,pdf,docx|max:10000', // Format file dan ukuran maksimal
    ]);

    // Menghasilkan kode acak untuk nama file
    $randomCode = rand(100000, 999999);

    // Mencari cart berdasarkan ID yang diterima
    $cart = Cart::find($request->id);
    
    if ($cart) {
        // Update kolom lampiran dengan kode acak
        $cart->lampiran = $randomCode . '.' . $request->file('file')->getClientOriginalExtension();
        $cart->save();  // Menyimpan perubahan ke database
        
        // Mengecek apakah ada file yang diupload
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Menyimpan file ke storage (misalnya public)
            $filePath = $file->storeAs('public/lampiran', $cart->lampiran);  // Simpan dengan nama yang sesuai

            // Mengembalikan respons sukses
            return response()->json([
                'message' => 'File uploaded successfully',
                'file' => $cart->lampiran,
            ]);
        }
    } else {
        return response()->json(['message' => 'Cart item not found'], 404);
    }
}
public function deleteCartItem($id)
{
    $cartItem = Cart::find($id);

    if ($cartItem) {
        // Menghapus item cart dari database
        $cartItem->delete();

        // Mengembalikan respon sukses
        return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
    }

    // Jika item tidak ditemukan
    return response()->json(['success' => false, 'message' => 'Item not found'], 404);
}

}
