<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaTim;
use App\Models\TentangKami;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TentangKamiController extends Controller
{
    public function index()
    {
        $tentang = TentangKami::first() ?? new TentangKami();
        $tim = AnggotaTim::latest()->get();

        return view('admin.tentang-kami.index', compact('tentang', 'tim'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'teks_kolaborasi_tim' => 'nullable|string',
            'teks_kolaborasi_serah_terima' => 'nullable|string',
            'foto_kolaborasi_tim' => 'nullable|image|mimes:png,jpg,jpeg,gif,bmp|max:2048',
            'foto_kolaborasi_serah_terima' => 'nullable|image|mimes:png,jpg,jpeg,gif,bmp|max:2048',
        ], [
            'foto_kolaborasi_tim.max' => 'Gagal: Ukuran foto tim terlalu besar. Maksimal 2 MB.',
            'foto_kolaborasi_tim.mimes' => 'Format foto tim harus berupa: png, jpg, jpeg, gif, atau bmp.',
            'foto_kolaborasi_serah_terima.max' => 'Gagal: Ukuran foto serah terima terlalu besar. Maksimal 2 MB.',
            'foto_kolaborasi_serah_terima.mimes' => 'Format foto serah terima harus berupa: png, jpg, jpeg, gif, atau bmp.',
        ]);

        try {
            $tentang = TentangKami::first() ?? new TentangKami();
            $data = $request->except(['_token', '_method', 'foto_kolaborasi_tim', 'foto_kolaborasi_serah_terima']);

            if ($request->hasFile('foto_kolaborasi_tim')) {
                $this->ensureLongTextColumn(TentangKami::class, 'foto_kolaborasi_tim');
                $this->deleteLegacyFile($tentang->foto_kolaborasi_tim);
                $data['foto_kolaborasi_tim'] = $this->encodeImageToBase64($request->file('foto_kolaborasi_tim'));
            }

            if ($request->hasFile('foto_kolaborasi_serah_terima')) {
                $this->ensureLongTextColumn(TentangKami::class, 'foto_kolaborasi_serah_terima');
                $this->deleteLegacyFile($tentang->foto_kolaborasi_serah_terima);
                $data['foto_kolaborasi_serah_terima'] = $this->encodeImageToBase64($request->file('foto_kolaborasi_serah_terima'));
            }

            TentangKami::updateOrCreate(['id' => 1], $data);

            return redirect()->back()->with('success', 'Informasi Tentang Kami berhasil diperbarui!');
        } catch (\Throwable $e) {
            Log::error('[TentangKami@updateProfil] ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['foto_kolaborasi_tim' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    public function storeTim(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:png,jpg,jpeg,gif,bmp|max:2048',
        ], [
            'foto.max' => 'Gagal: Ukuran foto anggota terlalu besar. Maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa: png, jpg, jpeg, gif, atau bmp.',
        ]);

        try {
            if ($request->hasFile('foto')) {
                $this->ensureLongTextColumn(AnggotaTim::class, 'foto');
                $data['foto'] = $this->encodeImageToBase64($request->file('foto'));
            }

            AnggotaTim::create($data);

            return redirect()->back()->with('success', 'Anggota tim berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error('[TentangKami@storeTim] ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan anggota: ' . $e->getMessage())->withErrors(['foto' => 'Gagal menyimpan anggota: ' . $e->getMessage()]);
        }
    }

    public function updateTim(Request $request, $id)
    {
        $tim = AnggotaTim::findOrFail($id);
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:png,jpg,jpeg,gif,bmp|max:2048',
        ], [
            'foto.max' => 'Gagal: Ukuran foto anggota terlalu besar. Maksimal 2 MB.',
            'foto.mimes' => 'Format foto harus berupa: png, jpg, jpeg, gif, atau bmp.',
        ]);

        try {
            if ($request->hasFile('foto')) {
                $this->ensureLongTextColumn(AnggotaTim::class, 'foto');
                $this->deleteLegacyFile($tim->foto);
                $data['foto'] = $this->encodeImageToBase64($request->file('foto'));
            }

            $tim->update($data);

            return redirect()->back()->with('success', 'Data anggota tim diperbarui.');
        } catch (\Throwable $e) {
            Log::error('[TentangKami@updateTim] ' . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['foto' => 'Gagal memperbarui anggota: ' . $e->getMessage()]);
        }
    }

    public function destroyTim($id)
    {
        $tim = AnggotaTim::findOrFail($id);
        $this->deleteLegacyFile($tim->foto);
        $tim->delete();

        return redirect()->back()->with('success', 'Anggota tim dihapus.');
    }

    private function ensureLongTextColumn(string $modelClass, string $column): void
    {
        $table = (new $modelClass())->getTable();
        $col = DB::selectOne(
            'SELECT DATA_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
            [$table, $column]
        );

        if ($col && strtolower((string) $col->DATA_TYPE) !== 'longtext') {
            DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` LONGTEXT NULL");
        }
    }

    private function encodeImageToBase64(UploadedFile $file): string
    {
        $mime = $file->getMimeType() ?: 'image/jpeg';
        $raw  = file_get_contents($file->getRealPath());

        // Kompres otomatis via GD jika tersedia agar ringan disimpan di database
        if (function_exists('imagecreatefromstring') && in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            $img = @imagecreatefromstring($raw);
            if ($img !== false) {
                $width  = imagesx($img);
                $height = imagesy($img);
                $maxDim = 800;

                if ($width > $maxDim || $height > $maxDim) {
                    $ratio     = min($maxDim / $width, $maxDim / $height);
                    $newWidth  = max(1, (int) round($width * $ratio));
                    $newHeight = max(1, (int) round($height * $ratio));
                    $resized   = imagecreatetruecolor($newWidth, $newHeight);
                    imagefill($resized, 0, 0, imagecolorallocate($resized, 255, 255, 255));
                    imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    imagedestroy($img);
                    $img = $resized;
                }

                ob_start();
                imagejpeg($img, null, 75);
                $compressed = ob_get_clean();
                imagedestroy($img);

                if ($compressed !== false && strlen($compressed) < strlen($raw)) {
                    return 'data:image/jpeg;base64,' . base64_encode($compressed);
                }
            }
        }

        return 'data:' . $mime . ';base64,' . base64_encode($raw);
    }

    private function deleteLegacyFile(?string $path): void
    {
        if (!$path || str_starts_with($path, 'data:')) {
            return;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        } catch (\Throwable $e) {
            // Abaikan error filesystem read-only di Vercel
        }
    }
}