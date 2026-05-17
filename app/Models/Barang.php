<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['kategori_id', 'nama_barang', 'harga', 'jumlah', 'foto'];

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    public function fakturItems()
    {
        return $this->hasMany(FakturItem::class, 'barang_id');
    }
}