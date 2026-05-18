<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment');
    }

    public function token()
    {
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = config('midtrans.isProduction');
        Config::$isSanitized = config('midtrans.isSanitized');
        Config::$is3ds = config('midtrans.is3ds');

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 10000,
            ],

            'customer_details' => [
                'first_name' => 'Naufal',
                'email' => 'naufal@gmail.com',
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json($snapToken);
    }
}