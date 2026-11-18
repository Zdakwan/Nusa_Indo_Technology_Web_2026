<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key sementara agar proses bersih-bersih tabel aman
        Schema::disableForeignKeyConstraints();

        // 1. Buat 3 Role Admin sesuai permintaan
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            [
                'id' => 1,
                'name' => 'SuperAdmin',
                'email' => 'SuperAdmin@nusaindotech',
                'phone' => '081234567890',
                'role' => 'super_admin',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'AdminKonten',
                'email' => 'AdminKonten@nusaindotech',
                'phone' => null,
                'role' => 'admin_konten',
                'password' => Hash::make('passwordkonten'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'AdminLayanan',
                'email' => 'AdminLayanan@nusaindotech',
                'phone' => null,
                'role' => 'admin_layanan',
                'password' => Hash::make('passwordlayanan'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. Pulihkan Data Layanan dari Database Lama
        DB::table('layanan')->truncate();
        DB::table('layanan')->insert([
            [
                'id' => 1,
                'nama_layanan' => 'IT Portfolio Management',
                'kategori' => 'TATA KELOLA',
                'deskripsi' => 'Perencanaan Strategis (High-Level) Jangka Panjang Pengembangan Sistem Informasi Perusahaan.',
                'icon' => 'fa-solid fa-folder-open',
                'created_at' => '2026-10-20 22:39:54',
                'updated_at' => '2026-11-03 13:45:10',
            ],
            [
                'id' => 2,
                'nama_layanan' => 'Training IT',
                'kategori' => 'UMUM',
                'deskripsi' => 'Kami dapat memberikan pelatihan teknologi informasi baik pada organisasi/perusahaan maupun individu.',
                'icon' => 'fa-solid fa-laptop-code',
                'created_at' => '2026-10-20 22:41:06',
                'updated_at' => '2026-11-03 13:44:41',
            ],
            [
                'id' => 3,
                'nama_layanan' => 'Enterprise Architecture',
                'kategori' => 'TATA KELOLA',
                'deskripsi' => 'Enterprise Architecture (EA) atau IT Architect. Pendekatan modern perencanaan SI/TI. IT Blueprint.',
                'icon' => 'fa-solid fa-building-columns',
                'created_at' => '2026-10-20 22:41:34',
                'updated_at' => '2026-11-03 13:44:55',
            ],
            [
                'id' => 4,
                'nama_layanan' => 'Software & Hardware Development',
                'kategori' => 'DEVELOPMENT',
                'deskripsi' => 'Kami Siap Untuk Memenuhi Kebutuhan Software dan Hardware Untuk Menunjang Bisnis Perusahaan Anda.',
                'icon' => 'fa-solid fa-laptop-code',
                'created_at' => '2026-10-20 22:41:52',
                'updated_at' => '2026-11-03 13:44:07',
            ],
            [
                'id' => 5,
                'nama_layanan' => 'IT Governance',
                'kategori' => 'TATA KELOLA',
                'deskripsi' => 'IT Management, IT Services dan Layanan Jasa Audit Assesment Strategis SI/TI dalam bentuk Audit IT.',
                'icon' => 'fa-solid fa-code',
                'created_at' => '2026-10-20 22:42:28',
                'updated_at' => '2026-11-03 13:27:58',
            ],
            [
                'id' => 6,
                'nama_layanan' => 'Multimedia',
                'kategori' => 'DEVELOPMENT',
                'deskripsi' => 'Kami Menyediakan Layanan : Pembuatan Company Profile Perusahaan, Editing Video, Animasi dan Fotografi.',
                'icon' => 'fa-solid fa-camera',
                'created_at' => '2026-10-20 22:42:45',
                'updated_at' => '2026-11-03 13:45:22',
            ],
        ]);

        // 3. Pulihkan Data Portofolio
        DB::table('portofolio')->truncate();
        DB::table('portofolio')->insert([
            [
                'id' => 1,
                'layanan_id' => null,
                'rating' => 0,
                'judul' => 'Flashcomindonesia.com',
                'deskripsi' => 'Flashcomindonesia.com  adalah lembaga penyedia layanan kursus dan pelatihan komputer serta teknologi informasi terkemuka di Indonesia. Platform ini menawarkan pembelajaran 100% praktis dengan materi yang dapat disesuaikan (custom), jadwal fleksibel, serta dibimbing oleh instruktur dari kalangan praktisi dan akademisi.',
                'fitur_utama' => null,
                'teknologi' => null,
                'nama_klien' => null,
                'link_project' => null,
                'tahun' => null,
                'kategori' => 'Lembaga Pembelajaran',
                'gambar' => 'portofolio/N315ypAFsN9gUSSzG4Rr7DP5xPLrhl4dxtzTZaJV.png',
                'created_at' => '2026-10-20 22:45:02',
                'updated_at' => '2026-10-20 22:45:02',
            ],
            [
                'id' => 2,
                'layanan_id' => null,
                'rating' => 0,
                'judul' => 'Dinas Kebudayaan Pariwisata Jatim',
                'deskripsi' => 'Aplikasi Pendataan Disbudpar Jatim',
                'fitur_utama' => null,
                'teknologi' => null,
                'nama_klien' => null,
                'link_project' => null,
                'tahun' => null,
                'kategori' => 'Aplikasi Pendataan Disbudpar Jatim',
                'gambar' => 'portofolio/0ABJrrWBWfsgRI05D2EnAGZDV1Jx3j0Dm7KBJwOm.png',
                'created_at' => '2026-10-20 22:47:55',
                'updated_at' => '2026-10-20 22:47:55',
            ],
            [
                'id' => 3,
                'layanan_id' => 4,
                'rating' => 5,
                'judul' => 'Telecollection Software',
                'deskripsi' => 'Agency Bank PT. Chrismalis Artha',
                'fitur_utama' => null,
                'teknologi' => 'Java',
                'nama_klien' => null,
                'link_project' => null,
                'tahun' => null,
                'kategori' => 'Agency Bank PT. Chrismalis Artha',
                'gambar' => 'portofolio/ELEKffzdefg6efuFRXWOLun0dORgICwRItcK5KYi.png',
                'created_at' => '2026-10-20 22:49:28',
                'updated_at' => '2026-10-24 00:00:35',
            ],
        ]);

        // 4. Pulihkan Data Pelatihan
        DB::table('pelatihan')->truncate();
        DB::table('pelatihan')->insert([
            [
                'id' => 1,
                'judul' => 'Pembelajaran IT Dasar Untuk Pelajar SMK',
                'deskripsi' => 'Pembelajaran IT Dasar Untuk Pelajar SMK',
                'harga' => 150000.00,
                'kategori' => 'Training IT',
                'gambar' => 'pelatihan/FB5z9QqYfL9s2MkUoWRuUCSx2MIG2Et4hRYK2dYK.jpg',
                'created_at' => '2026-10-24 00:22:27',
                'updated_at' => '2026-11-02 16:28:11',
            ],
        ]);

        // 5. Pulihkan Data Ensiklopedia
        DB::table('ensiklopedia')->truncate();
        DB::table('ensiklopedia')->insert([
            [
                'id' => 1,
                'judul' => 'Telecollection Software',
                'kategori' => 'IT Portfolio Management',
                'konten' => 'Lorem Ipsum',
                'tags' => null,
                'gambar' => null,
                'created_at' => '2026-10-26 19:32:00',
                'updated_at' => '2026-10-26 19:52:58',
            ],
        ]);

        // 6. Pulihkan Data Anggota Tim
        DB::table('anggota_tims')->truncate();
        DB::table('anggota_tims')->insert([
            [
                'id' => 1,
                'nama' => 'Muhamad Nauval Zdakwan Utomo',
                'jabatan' => 'Fullstack Dev',
                'foto' => 'tim/ZBM255HIqjbiBFjKBdoQFyXMz9Hlfyhuf0yGlKAH.png',
                'created_at' => '2026-11-01 23:18:57',
                'updated_at' => '2026-11-01 23:18:57',
            ],
            [
                'id' => 2,
                'nama' => 'Mey Lujeng Trisnawati',
                'jabatan' => 'FrontEnd Dev',
                'foto' => 'tim/CDPo61wTMYL8VbGPJg0BIfDGXZB7dtSbyiJijVjU.png',
                'created_at' => '2026-11-01 23:20:01',
                'updated_at' => '2026-11-01 23:20:01',
            ],
            [
                'id' => 3,
                'nama' => 'Muhammad Syahrul Mubarok',
                'jabatan' => 'BackEnd Dev',
                'foto' => 'tim/1H1WhuY4CfP0wgQ7Y3tyvG9kdhcxaMfS4ktECKEq.png',
                'created_at' => '2026-11-01 23:20:47',
                'updated_at' => '2026-11-01 23:20:47',
            ],
        ]);

        // 7. Pulihkan Data Tentang Kami
        DB::table('tentang_kamis')->truncate();
        DB::table('tentang_kamis')->insert([
            [
                'id' => 1,
                'judul' => 'Transformasi Digital Bersama Kami',
                'deskripsi' => 'Nusa Indo Tech hadir memberikan solusi teknologi inovatif, terstruktur, dan andal untuk membantu bisnis Anda berkembang secara eksponensial di era digital modern.',
                'visi' => 'Menjadi perusahaan konsultan IT terkemuka yang mendorong transformasi digital.',
                'misi' => "Mengembangkan perangkat lunak inovatif yang berorientasi pada penyelesaian masalah bisnis.\r\n\r\nMenyediakan layanan konsultasi dan arsitektur IT yang andal, aman, dan efisien.\r\n\r\nMendampingi klien secara profesional dalam setiap fase transformasi digital.",
                'alamat' => 'Rungkut Asri Timur XV H No.2, V No.2 Blk H, Rungkut Kidul, Kec. Rungkut, Surabaya, Jawa Timur 60293',
                'telepon' => '+62 878-5246-1990',
                'email' => 'nusaindotech@gmail.com',
                'gmaps_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.2149542553!2d112.7848928327933!3d-7.329738420023053!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7fa9551ec3715%3A0xb2f84d0e9424af79!2sNusa%20Indo%20Konsultan%20IT%20SURABAYA!5e0!3m2!1sid!2sid!4v1792986262810!5m2!1sid!2sid',
                'teks_kolaborasi_tim' => 'Sistem Informasi web dan aplikasi mobile native Nusa Indo Technology ini dirancang dan dikembangkan secara khusus melalui kerja sama dengan tim mahasiswa Teknik Informatika dari Politeknik Negeri Jember (Polije) PSDKU Kampus 4 Sidoarjo. Kolaborasi ini merupakan bentuk nyata penerapan ilmu rekayasa perangkat lunak untuk menghadirkan solusi teknologi industri yang terintegrasi.',
                'teks_kolaborasi_serah_terima' => 'Sistem ini dibuat bertujuan untuk mengoptimalkan proses bisnis dan manajemen layanan klien melalui digitalisasi terpusat. Momen serah terima ini menjadi tonggak sejarah kerja sama yang sukses, menghasilkan ekosistem digital yang modern, aman, dan siap beroperasi penuh untuk melayani klien Nusa Indo Technology.',
                'foto_kolaborasi_tim' => 'kolaborasi/FJo1GfB3YjGtktWY8Vf9xeGUYwdfACJ6fdoNCF17.png',
                'foto_kolaborasi_serah_terima' => null,
                'created_at' => '2026-10-25 20:46:31',
                'updated_at' => '2026-11-01 23:51:24',
            ],
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
