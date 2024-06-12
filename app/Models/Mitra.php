<?php

// Mendefinisikan namespace untuk kelas ini.
namespace App\Models;

// Menggunakan kelas Model dari CodeIgniter.
use CodeIgniter\Model;

// Mendefinisikan kelas `Mitra` yang merupakan turunan dari kelas `Model`.
class Mitra extends Model
{
    // Menentukan nama tabel yang akan digunakan oleh model ini.
    protected $table = 'mitra';
    
    // Menentukan nama kolom sebagai primary key pada tabel.
    protected $primaryKey = 'id';
    
    // Menentukan jenis data yang akan dikembalikan oleh fungsi-fungsi pada model ini.
    protected $returnType = 'array';
    
    // Menentukan kolom-kolom mana saja yang diperbolehkan untuk diisi melalui operasi create atau update.
    protected $allowedFields = ['user_id', 'nama_fithub', 'nama_pemilik', 'lokasi', 'telepon', 'alamat_fithub', 'email'];

    // Mendefinisikan fungsi `dataMitra()` untuk mengambil data mitra berdasarkan id pengguna (user_id).
    public function dataMitra($id_user)
    {
        // Memulai pembangunan query menggunakan Query Builder untuk mendapatkan data mitra berdasarkan id pengguna (user_id).
        return $this->select('mitra.*')
                        ->where('user_id', $id_user)
                        ->findAll();
    }

    // Mendefinisikan fungsi `fetchAllMitra()` untuk mengambil semua data mitra.
    public function fetchAllMitra()
    {
        // Memulai pembangunan query menggunakan Query Builder untuk mengambil semua data mitra.
        return $this->select('mitra.*')
                        ->findAll();
    }
}

?>
