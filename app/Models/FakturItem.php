<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FakturItem extends Model
{
    protected $table = 'faktur_item';
    protected $fillable = ['faktur_id', 'barang_id', 'kuantitas', 'subtotal'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function faktur()
    {
        return $this->belongsTo(Faktur::class, 'faktur_id');
    }
}