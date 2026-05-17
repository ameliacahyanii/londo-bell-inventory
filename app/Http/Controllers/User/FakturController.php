<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faktur;
use App\Models\FakturItem;
use App\Models\Barang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FakturController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum('subtotal');
        return view('user.invoice.cart', compact('cart', 'total'));
    }

    public function create()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.catalog')->with('error', 'Faktur kosong!');
        }
        $total = collect($cart)->sum('subtotal');
        return view('user.invoice.create', compact('cart', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alamat_pengiriman' => 'required|string|min:10|max:100',
            'kode_pos' => ['required', 'string', 'regex:/^[0-9]{5}$/'],
        ], [
            'kode_pos.regex' => 'Kode pos harus 5 digit angka.',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('user.catalog')->with('error', 'Faktur kosong!');
        }

        // Cek stok
        foreach ($cart as $item) {
            $barang = Barang::find($item['barang_id']);
            if (!$barang || $barang->jumlah < $item['kuantitas']) {
                return back()->with('error', "Stok {$item['nama_barang']} tidak mencukupi!");
            }
        }

        DB::transaction(function () use ($request, $cart) {
            $nomor = 'INV-' . strtoupper(uniqid());
            $total = collect($cart)->sum('subtotal');

            $faktur = Faktur::create([
                'user_id' => Auth::id(),
                'nomor_invoice' => $nomor,
                'alamat_pengiriman' => $request->alamat_pengiriman,
                'kode_pos' => $request->kode_pos,
                'total_harga' => $total,
            ]);

            foreach ($cart as $item) {
                FakturItem::create([
                    'faktur_id' => $faktur->id,
                    'barang_id' => $item['barang_id'],
                    'kuantitas' => $item['kuantitas'],
                    'subtotal' => $item['subtotal'],
                ]);
                // Kurangi stok
                Barang::find($item['barang_id'])->decrement('jumlah', $item['kuantitas']);
            }

            session()->forget('cart');
            session()->put('last_faktur_id', $faktur->id);
        });

        return redirect()->route('user.faktur.show', session('last_faktur_id'));
    }

    public function show(Faktur $faktur)
    {
        if ($faktur->user_id !== Auth::id())
            abort(403);
        $faktur->load('items.barang.kategori', 'user');
        return view('user.invoice.show', compact('faktur'));
    }

    public function history()
    {
        $faktur = Faktur::where('user_id', Auth::id())->latest()->get();
        return view('user.invoice.history', compact('faktur'));
    }
}