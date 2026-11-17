<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Services\MobileApi;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::latest()->paginate(10);
        return view('admin.layanan.index', compact('layanan'));
    }

    public function store(Request $request)
    {
        // Validasi sekaligus menampung data yang aman (termasuk icon)
        $data = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'icon'         => 'required|string|max:50',
            'deskripsi'    => 'required|string',
        ]);

        $layanan = Layanan::create($data);

        // Teruskan ke aplikasi mobile (gagal kirim tidak membatalkan simpan).
        app(MobileApi::class)->pushLayanan($layanan);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $layanan = Layanan::findOrFail($id);

        // Validasi sekaligus menampung data yang aman (termasuk icon)
        $data = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'icon'         => 'required|string|max:50',
            'deskripsi'    => 'required|string',
        ]);

        $layanan->update($data);

        app(MobileApi::class)->pushLayanan($layanan);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        $idWeb = $layanan->id;
        $layanan->delete();

        app(MobileApi::class)->deleteLayanan($idWeb);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
