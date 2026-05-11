<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class barang extends Model
{
    use HasFactory;
    protected $table = 'barang';
    protected $primaryKey = 'id';
    
    // Tambahkan kategori_id di sini
    protected $fillable = ['nama_barang','harga','bahan','ukuran','stok','gambar', 'kategori_id'];

    public function detail_pesanan() 
    {
        return $this->hasMany(detail_pesanan::class);
    }

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    public function detail()
{
    return $this->hasMany(detail_pesanan::class, 'barang_id');
}
}