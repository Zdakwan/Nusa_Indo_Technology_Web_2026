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
        $data = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'icon'         => 'required|string|max:50',
            'deskripsi'    => 'required|string',
        ]);

        $layanan = Layanan::create($data);

        app(MobileApi::class)->pushLayanan($layanan);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $layanan = Layanan::findOrFail($id);

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

    /**
     * Menghapus beberapa layanan sekaligus (Bulk Delete)
     */
    public function bulkDelete(Request $request)
    {
        // Validasi input array
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:layanans,id',
        ]);

        // Cari dan hapus semua data yang dipilih
        $layanans = Layanan::whereIn('id', $request->ids)->get();

        foreach ($layanans as $layanan) {
            $idWeb = $layanan->id;

            // Hapus dari database
            $layanan->delete();

            // Sinkronisasi Hapus ke Mobile API
            app(MobileApi::class)->deleteLayanan($idWeb);
        }

        return redirect()->route('admin.layanan.index')->with('success', count($request->ids) . ' layanan berhasil dihapus.');
    }
}
