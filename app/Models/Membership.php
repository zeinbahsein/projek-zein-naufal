<?php

// Mendefinisikan namespace untuk kelas ini.
namespace App\Models;

// Menggunakan kelas Model dari CodeIgniter.
use CodeIgniter\Model;

// Mendefinisikan kelas `Membership` yang merupakan turunan dari kelas `Model`.
class Membership extends Model
{
    // Menentukan nama tabel yang akan digunakan oleh model ini.
    protected $table = 'paket_membership';
    
    // Menentukan nama kolom sebagai primary key pada tabel.
    protected $primaryKey = 'id';
    
    // Menentukan jenis data yang akan dikembalikan oleh fungsi-fungsi pada model ini.
    protected $returnType = 'array';
    
    // Menentukan kolom-kolom mana saja yang diperbolehkan untuk diisi melalui operasi create atau update.
    protected $allowedFields = ['jangka_waktu', 'biaya_bulanan', 'biaya_total', 'keunggulan'];

    // Mendefinisikan fungsi `memberships()` untuk mengambil semua data paket keanggotaan.
    public function memberships()
    {
        // Mengembalikan semua data dari tabel `paket_membership`.
        return $this->findAll();
    }
}

?>
