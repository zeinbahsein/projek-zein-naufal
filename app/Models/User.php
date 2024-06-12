<?php

// Mendefinisikan namespace untuk kelas ini.
namespace App\Models;

// Menggunakan kelas Model dari CodeIgniter.
use CodeIgniter\Model;

// Mendefinisikan kelas `User` yang merupakan turunan dari kelas `Model`.
class User extends Model
{
    // Menentukan nama tabel yang akan digunakan oleh model ini.
    protected $table = 'users';
    
    // Menentukan nama kolom sebagai primary key pada tabel.
    protected $primaryKey = 'id';
    
    // Menentukan jenis data yang akan dikembalikan oleh fungsi-fungsi pada model ini.
    protected $returnType = 'array';
    
    // Menentukan kolom-kolom mana saja yang diperbolehkan untuk diisi melalui operasi create atau update.
    protected $allowedFields = ['username', 'password', 'role'];

    // Mendefinisikan fungsi `user()` untuk mengambil semua data pengguna.
    public function user()
    {   
        // Mengembalikan semua data dari tabel `users`.
        return $this->findAll();
    }
}

?>
