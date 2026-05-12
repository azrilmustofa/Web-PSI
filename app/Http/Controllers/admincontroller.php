<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class admincontroller extends Controller
{
    // =========================
    // DATA PENGGUNA
    // =========================
    public function index()
    {
        $users = User::latest()->get();

        return view('barang.datpen', compact('users'));
    }

    // =========================
    // TAMBAH USER
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan');
    }
}