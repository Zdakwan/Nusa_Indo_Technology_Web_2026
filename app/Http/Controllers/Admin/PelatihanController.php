<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Layanan;
use App\Models\Pelatihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PelatihanController extends Controller
{
    public function index()
    {
        $pelatihan = Pelatihan::latest()->paginate(10);
        $layanan = Layanan::all();

        return view('admin.pelatihan.index', compact('pelatihan', 'layanan'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga'     => 'required|numeric',
            'kategori'  => 'nullable|string|max:100',
            'gambar'    => 'nullable|image|mimes:png,jpg,jpeg,gif,bmp|max:2048',
        ], [
            'gambar.min'   => 'Gagal: Ukuran gambar terlalu kecil. Maksimal 2 MB.',
            'gambar.max'   => 'Gagal: Ukuran gambar terlalu besar. Maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa: png, jpg, jpeg, gif, atau bmp.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
        ]);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('pelatihan', 'public');
        } else {
            unset($data['gambar']);
        }

        Pelatihan::create($data);

        return redirect()->route('admin.pelatihan.index')->with('success', 'Pelatihan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        $data = $request->validate([
            'judul'     => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga'     => 'required|numeric',
            'kategori'  => 'nullable|string|max:100',
            'gambar'    => 'nullable|image|mimes:png,jpg,jpeg,gif,bmp|max:2048',
        ], [
            'gambar.min'   => 'Gagal: Ukuran gambar terlalu kecil. Maksimal 2 MB.',
            'gambar.max'   => 'Gagal: Ukuran gambar terlalu besar. Maksimal 2 MB.',
            'gambar.mimes' => 'Format gambar harus berupa: png, jpg, jpeg, gif, atau bmp.',
            'gambar.image' => 'File yang diunggah harus berupa gambar.',
        ]);

        if ($request->hasFile('gambar')) {
            if ($pelatihan->gambar && Storage::disk('public')->exists($pelatihan->gambar)) {
                Storage::disk('public')->delete($pelatihan->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('pelatihan', 'public');
        } else {
            unset($data['gambar']);
        }

        $pelatihan->update($data);

        return redirect()->route('admin.pelatihan.index')->with('success', 'Pelatihan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelatihan = Pelatihan::findOrFail($id);

        if ($pelatihan->gambar && Storage::disk('public')->exists($pelatihan->gambar)) {
            Storage::disk('public')->delete($pelatihan->gambar);
        }

        $pelatihan->delete();

        return redirect()->route('admin.pelatihan.index')->with('success', 'Pelatihan berhasil dihapus.');
    }

    /**
     * Menghapus beberapa pelatihan sekaligus beserta file gambarnya (Bulk Delete).
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:pelatihans,id',
        ]);

        $pelatihans = Pelatihan::whereIn('id', $request->ids)->get();

        foreach ($pelatihans as $pelatihan) {
            if ($pelatihan->gambar && Storage::disk('public')->exists($pelatihan->gambar)) {
                Storage::disk('public')->delete($pelatihan->gambar);
            }
            $pelatihan->delete();
        }

        return redirect()->back()->with('success', count($request->ids) . ' pelatihan berhasil dihapus beserta file gambarnya.');
    }
}
