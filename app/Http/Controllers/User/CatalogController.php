<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\KategoriBarang;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->search) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $barang = $query->latest()->paginate(12);
        $kategori = KategoriBarang::all();

        return view('user.catalog.index', compact('barang', 'kategori'));
    }

    public function addToCart(Request $request, Barang $barang)
    {
        if ($barang->jumlah <= 0) {
            return back()->with('error', 'Barang sudah habis, silakan tunggu hingga barang di-restock ulang.');
        }

        $request->validate(['kuantitas' => 'required|integer|min:1|max:' . $barang->jumlah]);

        $cart = session()->get('cart', []);
        $qty = $request->kuantitas;

        if (isset($cart[$barang->id])) {
            $newQty = $cart[$barang->id]['kuantitas'] + $qty;
            if ($newQty > $barang->jumlah) {
                return back()->with('error', 'Stok tidak mencukupi!');
            }
            $cart[$barang->id]['kuantitas'] = $newQty;
            $cart[$barang->id]['subtotal'] = $newQty * $barang->harga;
        } else {
            $cart[$barang->id] = [
                'barang_id' => $barang->id,
                'nama_barang' => $barang->nama_barang,
                'harga' => $barang->harga,
                'kuantitas' => $qty,
                'subtotal' => $qty * $barang->harga,
                'kategori' => $barang->kategori->nama_kategori,
                'foto' => $barang->foto,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Barang ditambahkan ke faktur!');
    }

    public function removeFromCart($barangId)
    {
        $cart = session()->get('cart', []);
        unset($cart[$barangId]);
        session()->put('cart', $cart);
        return back()->with('success', 'Barang dihapus dari faktur.');
    }

    public function updateCart(Request $request, $barangId)
    {
        $barang = Barang::findOrFail($barangId);
        $request->validate(['kuantitas' => 'required|integer|min:1|max:' . $barang->jumlah]);

        $cart = session()->get('cart', []);
        if (isset($cart[$barangId])) {
            $cart[$barangId]['kuantitas'] = $request->kuantitas;
            $cart[$barangId]['subtotal'] = $request->kuantitas * $barang->harga;
            session()->put('cart', $cart);
        }
        return back()->with('success', 'Kuantitas diupdate!');
    }
}