<?php 

// Mendefinisikan namespace untuk kelas ini. 
namespace App\Models;

// Menggunakan kelas Model dari CodeIgniter.
use CodeIgniter\Model;

// Mendefinisikan kelas `Configs` yang merupakan turunan dari kelas `Model`.
class Configs extends Model
{
    // Menentukan nama tabel yang akan digunakan oleh model ini.
    protected $table = 'configs'; 
    
    // Menentukan nama kolom sebagai primary key pada tabel.
    protected $primaryKey = 'id'; 
    
    // Menentukan jenis data yang akan dikembalikan oleh fungsi-fungsi pada model ini.
    protected $returnType = 'array'; 
    
    // Menentukan kolom-kolom mana saja yang diperbolehkan untuk diisi melalui operasi create atau update.
    protected $allowedFields = ['nama_aplikasi', 'deskripsi_aplikasi', 'nama_perusahaan', 'alamat', 'whatsapp','telepon', 'email','instagram', 'tiktok','facebook', 'created_at'];

    // Mendefinisikan fungsi `dataConfig()` untuk mengambil data konfigurasi terbaru.
    public function dataConfig()
    {
        // Mengambil satu baris data dari tabel `configs`, diurutkan berdasarkan kolom `created_at` secara descending (terbaru dulu).
        return $this->orderBy('created_at', 'DESC')->first();
    }
}

?>
