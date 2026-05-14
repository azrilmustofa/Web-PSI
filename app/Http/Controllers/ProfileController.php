<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pesanan; // Pastikan model Pesanan di-import
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil dan riwayat pesanan
     */
    public function index()
{
    $user = auth()->user();

    // Jika Role adalah Customer
    if ($user->role == 'customer') {
        // Ambil riwayat pesanan dari database
        $riwayat_pesanan = \App\Models\Pesanan::where('user_id', $user->id)
                            ->where('status', '!=', 0)
                            ->orderBy('tanggal', 'desc')
                            ->get();

        // Kirim variabel ke view profile_c
        return view('profile_c', compact('riwayat_pesanan'));
    } 
    
    // Jika Role adalah Admin
    elseif ($user->role == 'admin') {
        return view('profile');
    } 
    
    // Jika Role adalah Kasir
    elseif ($user->role == 'kasir') {
        return view('profile_k');
    }

    return redirect('/');
}

    /**
     * Update Password User
     */
    public function updatePassword(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
        ]);

        $user = User::findOrFail(Auth::id());

        // 2. Verifikasi Password Lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah!']);
        }

        // 3. Update ke database
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        // 4. Refresh session agar tidak logout otomatis
        $request->session()->put('password_hash_'.Auth::getDefaultDriver(), $user->password);

        return back()->with('success', 'Password berhasil diupdate di database!');
    }

    public function getDetail($id)
{
    try {
        // Nama tabel disesuaikan: 'detail_pesanan' join ke 'barang'
        $details = \DB::table('detail_pesanan')
            ->join('barang', 'barang.id', '=', 'detail_pesanan.barang_id')
            ->where('detail_pesanan.pesanan_id', $id)
            ->select(
                'barang.nama_barang', 
                'detail_pesanan.jumlah', 
                'detail_pesanan.jumlah_harga'
            )
            ->get();

        // Ambil data dari tabel 'pesanan'
        $pesanan = \DB::table('pesanan')->where('id', $id)->first();

        if (!$pesanan) {
            return response()->json(['error' => 'Data pesanan tidak ditemukan'], 404);
        }

        return response()->json([
            'details' => $details,
            'total' => number_format($pesanan->jumlah_harga, 0, ',', '.')
        ]);

    } catch (\Exception $e) {
        // Mengembalikan pesan error asli agar bisa dicek di Inspect Element > Network
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
