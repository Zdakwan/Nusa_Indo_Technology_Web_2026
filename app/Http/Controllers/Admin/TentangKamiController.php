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
use Illuminate\Support\Str;

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
                $data['foto_kolaborasi_tim'] = $this->encodeImageToBase64($request->file('foto_kolaborasi_tim'), 800, 75);
            }

            if ($request->hasFile('foto_kolaborasi_serah_terima')) {
                $this->ensureLongTextColumn(TentangKami::class, 'foto_kolaborasi_serah_terima');
                $this->deleteLegacyFile($tentang->foto_kolaborasi_serah_terima);
                $data['foto_kolaborasi_serah_terima'] = $this->encodeImageToBase64($request->file('foto_kolaborasi_serah_terima'), 800, 75);
            }

            TentangKami::updateOrCreate(['id' => 1], $data);

            return redirect()->back()->with('success', 'Informasi Tentang Kami berhasil diperbarui!');
        } catch (\Throwable $e) {
            $msg = $this->shortErrorMessage($e);
            Log::error('[TentangKami@updateProfil] ' . $msg);
            return redirect()->back()->withInput($request->except(['foto_kolaborasi_tim', 'foto_kolaborasi_serah_terima']))
                ->with('error', 'Gagal menyimpan profil: ' . $msg);
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
                $data['foto'] = $this->encodeImageToBase64($request->file('foto'), 500, 70);
            }

            AnggotaTim::create($data);

            return redirect()->back()->with('success', 'Anggota tim berhasil ditambahkan.');
        } catch (\Throwable $e) {
            $msg = $this->shortErrorMessage($e);
            Log::error('[TentangKami@storeTim] ' . $msg);
            return redirect()->back()->withInput($request->except('foto'))
                ->with('error', 'Gagal menyimpan anggota: ' . $msg);
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
                $data['foto'] = $this->encodeImageToBase64($request->file('foto'), 500, 70);
            }

            $tim->update($data);

            return redirect()->back()->with('success', 'Data anggota tim diperbarui.');
        } catch (\Throwable $e) {
            $msg = $this->shortErrorMessage($e);
            Log::error('[TentangKami@updateTim] ' . $msg);
            return redirect()->back()->withInput($request->except('foto'))
                ->with('error', 'Gagal memperbarui anggota: ' . $msg);
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
        try {
            $table = (new $modelClass())->getTable();
            DB::statement("ALTER TABLE `{$table}` MODIFY `{$column}` LONGTEXT NULL");
        } catch (\Throwable $e) {
            Log::warning('[TentangKami] Auto-alter skip: ' . $this->shortErrorMessage($e));
        }
    }

    private function shortErrorMessage(\Throwable $e): string
    {
        $msg = preg_replace('/data:image\/[a-zA-Z0-9+.-]+;base64,[A-Za-z0-9+\/=]+/', '[BASE64_IMAGE]', $e->getMessage());
        return Str::limit((string) $msg, 220);
    }

    private function encodeImageToBase64(UploadedFile $file, int $maxDim = 500, int $quality = 70): string
    {
        $mime = $file->getMimeType() ?: 'image/jpeg';
        $raw  = file_get_contents($file->getRealPath());

        if (function_exists('imagecreatefromstring') && in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            $img = @imagecreatefromstring($raw);
            if ($img !== false) {
                $width  = imagesx($img);
                $height = imagesy($img);

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
                imagejpeg($img, null, $quality);
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