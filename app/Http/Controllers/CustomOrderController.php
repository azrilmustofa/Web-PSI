<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomOrder;

class CustomOrderController extends Controller
{
    // DASHBOARD KASIR
    public function index()
    {
        $data = CustomOrder::latest()->get();

        return view('kasir.index', compact('data'));
    }

    // CUSTOMER REQUEST CUSTOM
    public function store(Request $request)
    {
        CustomOrder::create([

            'user_id' => auth()->id(),

            'jenis_furniture' => $request->jenis_furniture,

            'jenis_kayu' => $request->jenis_kayu,

            'ukuran' => $request->ukuran,

            'catatan' => $request->catatan,

            'status' => 'Pending',

        ]);

        return back()->with(
            'success',
            'Custom order berhasil dikirim'
        );
    }

    // UPDATE STATUS KASIR
    public function status($id, Request $request)
    {
        $order = CustomOrder::findOrFail($id);

        $order->status = $request->status;

        $order->save();

        return back()->with(
            'success',
            'Status berhasil diupdate'
        );
    }
    
}