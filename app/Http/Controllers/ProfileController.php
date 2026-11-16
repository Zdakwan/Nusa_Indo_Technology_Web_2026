<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI UPLOAD FOTO PROFIL
    |--------------------------------------------------------------------------
    |
    | Semua pengaturan format dan ukuran foto berada di sini.
    | Ukuran maksimal dihitung dalam KB.
    |
    */

    private const AVATAR_MAX_KB = 2048;

    private const AVATAR_ALLOWED_MIMES = [
        'jpg',
        'jpeg',
        'png',
    ];

    private const AVATAR_ALLOWED_TYPES = [
        'image/jpeg',
        'image/png',
    ];

    /**
     * Mengambil konfigurasi upload foto profil.
     */
    private function avatarConfig(): array
    {
        return [
            'maxKb' => self::AVATAR_MAX_KB,
            'maxBytes' => self::AVATAR_MAX_KB * 1024,
            'maxMb' => self::AVATAR_MAX_KB / 1024,
            'mimes' => self::AVATAR_ALLOWED_MIMES,
            'mimeTypes' => self::AVATAR_ALLOWED_TYPES,

            'accept' => implode(',', array_map(
                fn ($mime) => '.' . $mime,
                self::AVATAR_ALLOWED_MIMES
            )),
        ];
    }

    /**
     * Halaman profil client.
     */
    public function index()
    {
        $client = Auth::guard('client')->user();

        return view('profile.index', compact('client'));
    }

    /**
     * Halaman edit profil client.
     */
    public function edit()
    {
        $client = Auth::guard('client')->user();

        $avatarConfig = $this->avatarConfig();

        return view('profile.edit', compact(
            'client',
            'avatarConfig'
        ));
    }

    /**
     * Memperbarui profil client.
     */
    public function update(Request $request)
    {
        $client = Auth::guard('client')->user();

        $avatarConfig = $this->avatarConfig();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:clients,email,' . $client->id,
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'company' => [
                'nullable',
                'string',
                'max:255',
            ],

            'position' => [
                'nullable',
                'string',
                'max:255',
            ],

            // Validasi foto profil
            'avatar' => [
                'nullable',
                'image',
                'mimes:' . implode(',', $avatarConfig['mimes']),
                'max:' . $avatarConfig['maxKb'],
            ],

        ], [
            'avatar.image' => 'File yang diunggah harus berupa gambar.',

            'avatar.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',

            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        $oldAvatar = $client->avatar;
        $newAvatar = null;

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FOTO BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('avatar')) {

            $newAvatar = $request->file('avatar')
                ->store('avatars', 'public');

            if (!$newAvatar) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'avatar' => 'Foto gagal disimpan. Silakan coba kembali.',
                    ]);
            }

            $validated['avatar'] = $newAvatar;
        }

        /*
        |--------------------------------------------------------------------------
        | PERBARUI DATA CLIENT
        |--------------------------------------------------------------------------
        */

        $client->update($validated);

        /*
        |--------------------------------------------------------------------------
        | HAPUS FOTO LAMA
        |--------------------------------------------------------------------------
        |
        | Foto lama dihapus hanya jika foto baru berhasil disimpan.
        |
        */

        if (
            $newAvatar &&
            $oldAvatar &&
            $oldAvatar !== $newAvatar &&
            Storage::disk('public')->exists($oldAvatar)
        ) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Menghapus foto profil client.
     */
    public function deleteAvatar()
    {
        $client = Auth::guard('client')->user();

        $oldAvatar = $client->avatar;

        $client->update([
            'avatar' => null,
        ]);

        if (
            $oldAvatar &&
            Storage::disk('public')->exists($oldAvatar)
        ) {
            Storage::disk('public')->delete($oldAvatar);
        }

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Foto profil berhasil dihapus!');
    }
}