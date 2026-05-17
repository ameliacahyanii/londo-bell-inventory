<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Faktur;
use App\Models\User;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Stat cards
        $totalBarang = Barang::count();
        $totalPelanggan = User::where('role', 'user')->count();
        $totalFaktur = Faktur::count();
        $totalPendapatan = Faktur::sum('total_harga');
        $stokHabis = Barang::where('jumlah', 0)->count();
        $stokMenipis = Barang::where('jumlah', '>', 0)->where('jumlah', '<=', 5)->count();

        // Faktur 7 hari terakhir (untuk grafik)
        $fakturHarian = Faktur::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(*) as jumlah_faktur'),
            DB::raw('SUM(total_harga) as total')
        )
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Barang terlaris (dari faktur_item)
        $barangTerlaris = DB::table('faktur_item')
            ->join('barang', 'faktur_item.barang_id', '=', 'barang.id')
            ->select('barang.nama_barang', DB::raw('SUM(faktur_item.kuantitas) as total_terjual'))
            ->groupBy('barang.id', 'barang.nama_barang')
            ->orderByDesc('total_terjual')
            ->limit(5)
            ->get();

        // Stok per kategori
        $stokKategori = DB::table('barang')
            ->join('kategori_barang', 'barang.kategori_id', '=', 'kategori_barang.id')
            ->select('kategori_barang.nama_kategori', DB::raw('SUM(barang.jumlah) as total_stok'))
            ->groupBy('kategori_barang.id', 'kategori_barang.nama_kategori')
            ->get();

        // Faktur terbaru
        $fakturTerbaru = Faktur::with('user')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBarang',
            'totalPelanggan',
            'totalFaktur',
            'totalPendapatan',
            'stokHabis',
            'stokMenipis',
            'fakturHarian',
            'barangTerlaris',
            'stokKategori',
            'fakturTerbaru'
        ));
    }
}