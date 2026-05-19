<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class pesanan extends Model
{
    use HasFactory;
    protected $table = 'pesanan';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'tanggal',
        'status',
        'jumlah_harga',
        'kode',
        'nama_penerima',
        'no_telepon',
        'alamat',
        'kota',
        'kode_pos',
        'metode_pembayaran',
        'catatan'
    ];

    public function detail()
    {
        return $this->hasMany(detail_pesanan::class, 'pesanan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
