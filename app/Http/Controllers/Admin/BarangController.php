<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\KategoriBarang;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('kategori')->latest()->paginate(10);
        return view('admin.barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = KategoriBarang::all();
        return view('admin.barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_barang,id',
            'nama_barang' => 'required|string|min:5|max:80',
            'harga' => 'required|integer|min:0',
            'jumlah' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('barang', 'public');
        }

        Barang::create([
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'jumlah' => $request->jumlah,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit(Barang $barang)
    {
        $kategori = KategoriBarang::all();
        return view('admin.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_barang,id',
            'nama_barang' => 'required|string|min:5|max:80',
            'harga' => 'required|integer|min:0',
            'jumlah' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $fotoPath = $barang->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath)
                Storage::disk('public')->delete($fotoPath);
            $fotoPath = $request->file('foto')->store('barang', 'public');
        }

        $barang->update([
            'kategori_id' => $request->kategori_id,
            'nama_barang' => $request->nama_barang,
            'harga' => $request->harga,
            'jumlah' => $request->jumlah,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil diupdate!');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->foto)
            Storage::disk('public')->delete($barang->foto);
        $barang->delete();
        return redirect()->route('admin.barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}