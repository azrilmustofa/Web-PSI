<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admincontroller;
use App\Http\Controllers\laporan;
use App\Http\Controllers\customercontroller;
use App\Http\Controllers\barangcontroller;
use App\Http\Controllers\pesanancontroller;
use App\Models\barang;
use App\Http\Middleware\admin;
use App\Http\Middleware\customer;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\PaymentController;

Route::get('/payment', [PaymentController::class, 'index']);

Route::get('/payment-token', [PaymentController::class, 'token']);

// Ganti Route::post menjadi Route::put
Route::put('/reset-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');
Route::middleware('auth')->get('/redirect-role', function () {
    
    if(auth()->user()->role == 'admin'){
        return redirect()->route('barang.index');
    }

    if(auth()->user()->role == 'kasir'){
        return redirect()->route('kasir.index');
    }

    return redirect()->route('customer.index');

});
// Jika sekarang begini:
Route::get('/admin', [barangcontroller::class, 'index'])->name('barang.index');

// Kamu bisa biarkan saja, asal di barangcontroller sudah ditambah variabel $kategori seperti langkah 1 di atas.


Route::get('/', [barangcontroller::class, 'home'])->name('customer.index');
Route::get('/detail/{id}', [barangcontroller::class, 'detail'])
    ->name('customer.detail');

// Route::get('/', [customercontroller::class, 'index'])->name('home');
// Route::get('/shop', [customercontroller::class, 'shop'])->name('customer.shop');
// Route::get('/about', [customercontroller::class, 'about'])->name('customer.about');
// Route::get('/contact', [customercontroller::class, 'contact'])->name('customer.contact');

Route::get('/about', [barangcontroller::class, 'about'])->name('customer.about'); 
Route::get('/shop/{kategori?}', [barangcontroller::class, 'shop'])->name('customer.shop');
Route::get('/custom', [barangcontroller::class, 'custom'])->name('customer.custom');
Route::get('/kategori/{slug}', [barangcontroller::class, 'kategori'])->name('customer.kategori');
Route::get('/contact', [barangcontroller::class, 'contact'])->name('customer.contact'); 
Route::middleware(['auth','customer'])->group(function () {
    Route::post('/custom-orders', [barangcontroller::class, 'storeCustom'])
        ->name('custom-orders.store');
    Route::get('/checkout', [pesanancontroller::class, 'checkout'])
    ->name('customer.checkout');
    Route::post('/pesan/{id}', [pesanancontroller::class, 'pesan'])
    ->name('pesan');  
    Route::get('/keranjang', [pesanancontroller::class, 'checkout'])
    ->name('customer.keranjang');
    Route::post('/quick-add/{id}', [pesanancontroller::class, 'quickAdd'])
    ->name('quick.add');
    Route::post('/checkout/bayar', [pesanancontroller::class, 'bayar'])
    ->name('checkout.bayar');
    Route::post('/checkout/bayar', [pesanancontroller::class, 'bayar'])
    ->name('checkout.bayar');
    Route::delete('/checkout/hapus/{id}', [pesanancontroller::class, 'hapus'])
    ->name('checkout.hapus');
    Route::post('/checkout/tambah/{id}', [pesananController::class, 'tambah'])->name('checkout.tambah');
    Route::post('/checkout/kurang/{id}', [pesananController::class, 'kurang'])->name('checkout.kurang');




    // Route::post('/pesan/{id}', [pesanancontroller::class,'pesan'])->name('pesan')->middleware('auth');
    // Route::get('/keranjang', [pesanancontroller::class,'checkout'])->name('customer.keranjang');
    // Route::delete('/keranjang/{id}', [pesanancontroller::class,'hapus'])->name('keranjang.hapus');
    // Route::get('/barang/{id}', [barangcontroller::class, 'detail'])->name('barang.detail');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [barangcontroller::class, 'index'])->name('barang.index');
    Route::get('/tambah-barang', [barangcontroller::class, 'create'])->name('barang.create');
    Route::post('/simpan-barang', [barangcontroller::class, 'store'])->name('barang.store');
    Route::post('/update-barang/{id}', [barangcontroller::class, 'update'])->name('barang.update');
    Route::delete('/hapus-barang/{id}', [barangcontroller::class, 'destroy'])->name('barang.destroy');
    Route::get('/laporan', [laporan::class, 'index'])->name('laporan.index');
    Route::get('/data-pengguna', [admincontroller::class, 'index'])->name('datpen.index');
    Route::post('/data-pengguna/store', [admincontroller::class, 'store'])
        ->name('datpen.store');
    Route::post('/simpan-kategori', [barangcontroller::class, 'storeKategori'])->name('kategori.store');
    Route::post('/simpan-bahan', [barangcontroller::class, 'storeBahan'])->name('bahan.store');
    
});

// Lempar semua urusan profil ke Controller
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth')->name('profile');
// ATAU jika menggunakan Controller (Disarankan)
// Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::get('/profile/detail/{id}', [ProfileController::class, 'getDetail'])->middleware('auth');
Route::middleware(['auth', 'kasir'])->group(function () {

    Route::get('/dashboard-kasir', [CustomOrderController::class, 'index'])
        ->name('kasir.index');

    Route::post('/custom-order/{id}/status', [CustomOrderController::class, 'status'])
        ->name('custom.status');

});