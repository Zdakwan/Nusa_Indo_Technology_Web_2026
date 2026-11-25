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
<<<<<<< HEAD
        ]);

        $layanan = Layanan::create($data);
=======
            // Batasi format ke jpeg, png, jpg, gif
            'icon'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $data = $request->only(['nama_layanan', 'kategori', 'deskripsi']);
>>>>>>> mey

        app(MobileApi::class)->pushLayanan($layanan);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

<<<<<<< HEAD
    public function update(Request $request, string $id)
=======
    // Sebelumnya: public function update(Request $request, $id)
public function update(Request $request, string $id)
>>>>>>> mey
    {
        $layanan = Layanan::findOrFail($id);

        $data = $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'icon'         => 'required|string|max:50',
            'deskripsi'    => 'required|string',
<<<<<<< HEAD
        ]);

=======
            // Batasi format ke jpeg, png, jpg, gif
            'icon'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        $data = $request->only(['nama_layanan', 'kategori', 'deskripsi']);

        if ($request->hasFile('icon')) {
            // Hapus file lama jika ada
            if ($layanan->icon && Storage::disk('public')->exists($layanan->icon)) {
                Storage::disk('public')->delete($layanan->icon);
            }
            $data['icon'] = $request->file('icon')->store('layanan', 'public');
        }

>>>>>>> mey
        $layanan->update($data);

        app(MobileApi::class)->pushLayanan($layanan);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

<<<<<<< HEAD
    public function destroy(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        $idWeb = $layanan->id;
=======
    // Sebelumnya: public function destroy($id)
public function destroy(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        // Hapus file dari storage saat layanan dihapus
        if ($layanan->icon && Storage::disk('public')->exists($layanan->icon)) {
            Storage::disk('public')->delete($layanan->icon);
        }

>>>>>>> mey
        $layanan->delete();

        app(MobileApi::class)->deleteLayanan($idWeb);

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
<<<<<<< HEAD

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
=======
}
>>>>>>> mey
