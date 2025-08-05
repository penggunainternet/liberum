<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Thread;
use App\Models\Category;
use Illuminate\Database\Seeder;

class PeraturanThreadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $peraturanCategory = Category::where('slug', 'peraturan')->first();

        if ($admin && $peraturanCategory) {
            Thread::create([
                'title' => 'Peraturan dan Tata Tertib Forum Literasi Liberum',
                'slug' => 'peraturan-tata-tertib-forum-literasi',
                'body' => '<h3>📚 Selamat Datang di Forum Literasi Liberum!</h3>

<p>Untuk menjaga kenyamanan dan kualitas diskusi, mohon patuhi peraturan berikut:</p>

<h4>🔰 PERATURAN UMUM</h4>
<ol>
<li><strong>Sopan dan Santun:</strong> Gunakan bahasa yang sopan dan menghormati sesama member</li>
<li><strong>No SARA:</strong> Tidak boleh mengandung unsur SARA, politik, atau konten negatif</li>
<li><strong>Stay On Topic:</strong> Diskusi harus sesuai dengan kategori yang dipilih</li>
<li><strong>No Spam:</strong> Jangan posting berulang atau promosi berlebihan</li>
<li><strong>Original Content:</strong> Berikan credit jika mengutip atau merepost dari sumber lain</li>
</ol>

<h4>📖 PANDUAN POSTING</h4>
<ul>
<li><strong>Review Buku:</strong> Berikan rating, synopsis singkat, dan pendapat objektif</li>
<li><strong>Rekomendasi:</strong> Jelaskan mengapa buku tersebut layak dibaca</li>
<li><strong>Diskusi Sastra:</strong> Analisis mendalam tentang karya, tokoh, atau tema</li>
<li><strong>Spoiler Alert:</strong> Gunakan warning untuk spoiler penting</li>
</ul>

<h4>🖼️ UPLOAD GAMBAR</h4>
<ul>
<li>Maksimal 5 gambar per posting</li>
<li>Total ukuran maksimal 15MB</li>
<li>Format: JPEG, PNG, GIF, WebP</li>
<li>Hindari gambar yang tidak relevan</li>
</ul>

<h4>⚠️ PELANGGARAN & SANKSI</h4>
<ul>
<li><strong>Peringatan 1:</strong> Teguran dari moderator</li>
<li><strong>Peringatan 2:</strong> Suspend 3 hari</li>
<li><strong>Peringatan 3:</strong> Suspend 7 hari</li>
<li><strong>Pelanggaran Berat:</strong> Ban permanen</li>
</ul>

<h4>💡 TIPS BERKUALITAS</h4>
<ul>
<li>Gunakan judul yang jelas dan deskriptif</li>
<li>Pilih kategori yang sesuai</li>
<li>Sertakan cover buku jika memungkinkan</li>
<li>Berpartisipasi aktif dalam diskusi</li>
<li>Apresiasi kontribusi member lain dengan like</li>
</ul>

<p><strong>📞 Kontak Moderator:</strong><br>
Jika ada pertanyaan atau laporan, hubungi admin melalui pesan pribadi.</p>

<p><em>Mari bersama-sama membangun komunitas literasi yang positif dan berkualitas! 🌟</em></p>',
                'category_id' => $peraturanCategory->id,
                'author_id' => $admin->id
            ]);
        }
    }
}
