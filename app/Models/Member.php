<?php

// Mendefinisikan namespace untuk kelas ini.
namespace App\Models;

// Menggunakan kelas Model dari CodeIgniter.
use CodeIgniter\Model;

// Mendefinisikan kelas `Member` yang merupakan turunan dari kelas `Model`.
class Member extends Model
{
    // Menentukan nama tabel yang akan digunakan oleh model ini.
    protected $table = 'members';
    
    // Menentukan nama kolom sebagai primary key pada tabel.
    protected $primaryKey = 'id';
    
    // Menentukan jenis data yang akan dikembalikan oleh fungsi-fungsi pada model ini.
    protected $returnType = 'array';
    
    // Menentukan kolom-kolom mana saja yang diperbolehkan untuk diisi melalui operasi create atau update.
    protected $allowedFields = ['nama', 'email', 'telepon', 'jenis_kelamin', 'id_paket_membership', 'id_mitra', 'tanggal_daftar', 'paket_berakhir'];

    // Mendefinisikan fungsi `getMembersWithDetails()` untuk mengambil data member dengan detailnya.
    public function getMembersWithDetails($mitraId = null)
    {
        // Memulai pembangunan query menggunakan Query Builder.
        $builder = $this->db->table($this->table);
        
        // Memilih kolom-kolom yang akan ditampilkan, termasuk kolom jangka_waktu dari tabel `paket_membership` dan kolom username dari tabel `users`.
        $builder->select('members.*, paket_membership.jangka_waktu, users.username');
        
        // Melakukan join dengan tabel `paket_membership` untuk mendapatkan informasi jangka waktu paket membership.
        $builder->join('paket_membership', 'paket_membership.id = members.id_paket_membership', 'left');
        
        // Melakukan join dengan tabel `users` untuk mendapatkan informasi username mitra.
        $builder->join('users', 'users.id = members.id_mitra', 'left');

        // Jika $mitraId tidak null, tambahkan kondisi ke $mitraId.
        if ($mitraId !== null) {
            $builder->where('members.id_mitra', $mitraId);
        }

        // Eksekusi query dan mengembalikan hasil dalam bentuk array.
        return $builder->get()->getResultArray();
    }
}
