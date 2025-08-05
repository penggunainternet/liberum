<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Thread;
use App\Models\Category;
use Illuminate\Database\Seeder;

class PanduanForumSeeder extends Seeder
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
            // Thread 1: Cara Menulis Review Buku yang Baik
            Thread::create([
                'title' => 'Panduan: Cara Menulis Review Buku yang Berkualitas',
                'slug' => 'panduan-cara-menulis-review-buku-berkualitas',
                'body' => '<h3>📝 Panduan Menulis Review Buku yang Berkualitas</h3>

<p>Review buku yang baik dapat membantu sesama pecinta buku dalam memilih bacaan. Berikut panduannya:</p>

<h4>🎯 STRUKTUR REVIEW</h4>
<ol>
<li><strong>Info Dasar:</strong> Judul, penulis, penerbit, tahun terbit, jumlah halaman</li>
<li><strong>Genre & Rating:</strong> Tentukan genre dan berikan rating 1-5 bintang ⭐</li>
<li><strong>Synopsis Singkat:</strong> Ceritakan plot tanpa spoiler mayor</li>
<li><strong>Analisis:</strong> Karakter, alur, gaya bahasa, tema</li>
<li><strong>Kelebihan & Kekurangan:</strong> Yang disukai dan tidak disukai</li>
<li><strong>Rekomendasi:</strong> Untuk siapa buku ini cocok</li>
</ol>

<h4>✅ DO (LAKUKAN)</h4>
<ul>
<li>Berikan pendapat yang jujur dan objektif</li>
<li>Sertakan quote menarik dari buku</li>
<li>Upload foto cover buku</li>
<li>Pilih kategori yang sesuai genre</li>
<li>Bandingkan dengan karya penulis lain jika perlu</li>
</ul>

<h4>❌ DON\'T (JANGAN)</h4>
<ul>
<li>Spoiler ending atau twist penting tanpa warning</li>
<li>Copy-paste review dari website lain</li>
<li>Hanya menulis "bagus" atau "jelek" tanpa alasan</li>
<li>Menyerang penulis secara personal</li>
</ul>

<h4>💡 TIPS TAMBAHAN</h4>
<ul>
<li>Baca buku sampai selesai sebelum mereview</li>
<li>Catat poin-poin penting saat membaca</li>
<li>Diskusikan dengan member lain di komentar</li>
<li>Update review jika pandangan berubah setelah diskusi</li>
</ul>

<p><em>Selamat mereview! Kontribusi Anda sangat berharga untuk komunitas literasi kita 📚</em></p>',
                'category_id' => $peraturanCategory->id,
                'author_id' => $admin->id
            ]);

            // Thread 2: Etika Diskusi Sastra
            Thread::create([
                'title' => 'Etika Diskusi Sastra dan Kritik Literatur',
                'slug' => 'etika-diskusi-sastra-kritik-literatur',
                'body' => '<h3>🎭 Etika Diskusi Sastra dan Kritik Literatur</h3>

<p>Diskusi sastra yang berkualitas membutuhkan pemahaman yang mendalam dan etika yang baik. Mari kita ciptakan ruang diskusi yang konstruktif!</p>

<h4>📚 PRINSIP DISKUSI SASTRA</h4>
<ol>
<li><strong>Respect Diversity:</strong> Hormati interpretasi dan pandangan yang berbeda</li>
<li><strong>Evidence-Based:</strong> Dukung argumen dengan bukti dari teks</li>
<li><strong>Open Mind:</strong> Buka diri terhadap perspektif baru</li>
<li><strong>Constructive Criticism:</strong> Kritik yang membangun, bukan menjatuhkan</li>
</ol>

<h4>🗣️ CARA BERDISKUSI YANG BAIK</h4>
<ul>
<li><strong>Quote Specific:</strong> Kutip bagian tertentu dari karya</li>
<li><strong>Explain Why:</strong> Jelaskan alasan di balik interpretasi Anda</li>
<li><strong>Ask Questions:</strong> Ajukan pertanyaan yang memancing diskusi</li>
<li><strong>Build On Ideas:</strong> Kembangkan ide dari komentar sebelumnya</li>
</ul>

<h4>⚖️ KRITIK YANG KONSTRUKTIF</h4>
<ul>
<li>Fokus pada karya, bukan menyerang penulis secara personal</li>
<li>Berikan alasan yang jelas untuk setiap kritik</li>
<li>Akui jika ada aspek positif meski secara keseluruhan tidak suka</li>
<li>Terima bahwa tidak semua karya cocok untuk semua orang</li>
</ul>

<h4>🚫 HINDARI</h4>
<ul>
<li>Menganggap interpretasi sendiri sebagai satu-satunya yang benar</li>
<li>Menyerang pribadi member lain</li>
<li>Diskusi off-topic yang tidak relevan</li>
<li>Spoiler tanpa warning yang jelas</li>
</ul>

<h4>📖 CONTOH DISKUSI BERKUALITAS</h4>
<blockquote>
"Menurut saya, karakter tokoh utama dalam novel ini menunjukkan perkembangan yang menarik. Di awal cerita, dia tampak egois (hal. 45), tapi setelah peristiwa X, ada perubahan signifikan yang terlihat dari dialognya di hal. 234. Bagaimana pendapat kalian tentang transformasi karakter ini?"
</blockquote>

<p><em>Mari jadikan setiap diskusi sebagai kesempatan untuk belajar dan memperkaya pemahaman kita tentang sastra! 🌟</em></p>',
                'category_id' => $peraturanCategory->id,
                'author_id' => $admin->id
            ]);
        }
    }
}
