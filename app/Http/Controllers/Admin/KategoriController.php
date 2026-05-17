<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriBarang;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::withCount('barang')->latest()->get();
        return view('admin.kategori.index', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:100']);
        KategoriBarang::create($request->only('nama_kategori'));
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori ditambahkan!');
    }

    public function update(Request $request, KategoriBarang $kategori)
    {
        $request->validate(['nama_kategori' => 'required|string|max:100']);
        $kategori->update($request->only('nama_kategori'));
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori diupdate!');
    }

    public function destroy(KategoriBarang $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori dihapus!');
    }
}