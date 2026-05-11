<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    protected $fillable = [

        'user_id',
        'jenis_furniture',
        'jenis_kayu',
        'ukuran',
        'catatan',
        'status'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}