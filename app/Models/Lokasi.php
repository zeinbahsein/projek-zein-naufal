<?php

// Mendefinisikan namespace untuk kelas ini.
namespace App\Models;

// Menggunakan kelas Model dari CodeIgniter.
use CodeIgniter\Model;

// Mendefinisikan kelas `Lokasi` yang merupakan turunan dari kelas `Model`.
class Lokasi extends Model
{
    // Menentukan nama tabel yang akan digunakan oleh model ini.
    protected $table = 'mitra';
    
    // Menentukan nama kolom sebagai primary key pada tabel.
    protected $primaryKey = 'id';
    
    // Menentukan jenis data yang akan dikembalikan oleh fungsi-fungsi pada model ini.
    protected $returnType = 'array';

    // Mendefinisikan fungsi `getLokasiOnly()` untuk mengambil semua data lokasi.
    public function getLokasiOnly()
    {   
        // Mengembalikan semua data dari tabel `mitra`.
        return $this->findAll();
    }
}

?>
