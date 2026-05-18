<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomOrder;

use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function show($id)
    {
        // Ambil custom order
        $custom = CustomOrder::findOrFail($id);

        // CONFIG MIDTRANS
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // DATA TRANSAKSI
        $params = [

            'transaction_details' => [

                'order_id' => 'CUSTOM-' . $custom->id . '-' . time(),

                'gross_amount' => (int) $custom->estimasi_harga,

            ],

            'customer_details' => [

                'first_name' => auth()->user()->name,

                'email' => auth()->user()->email,

            ],

            'item_details' => [

                [

                    'id' => $custom->id,

                    'price' => (int) $custom->estimasi_harga,

                    'quantity' => 1,

                    'name' => $custom->jenis_furniture,

                ]

            ]

        ];

        // GENERATE SNAP TOKEN
        $snapToken = Snap::getSnapToken($params);

        return view(
            'payment.payment',
            compact(
                'custom',
                'snapToken'
            )
        );
    }
}