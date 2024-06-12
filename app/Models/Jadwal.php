<?php

// Mendefinisikan namespace untuk kelas ini.
namespace App\Models;

// Menggunakan kelas Model dari CodeIgniter.
use CodeIgniter\Model;

// Mendefinisikan kelas `Jadwal` yang merupakan turunan dari kelas `Model`.
class Jadwal extends Model
{
    // Menentukan nama tabel yang akan digunakan oleh model ini.
    protected $table = 'jadwal';
    
    // Menentukan nama kolom sebagai primary key pada tabel.
    protected $primaryKey = 'id';
    
    // Menentukan jenis data yang akan dikembalikan oleh fungsi-fungsi pada model ini.
    protected $returnType = 'array';
    
    // Menentukan kolom-kolom mana saja yang diperbolehkan untuk diisi melalui operasi create atau update.
    protected $allowedFields = ['mitra_id', 'nama_latihan', 'trainer', 'jam_kegiatan', 'level_kesulitan', 'durasi_latihan', 'tanggal'];

    // Mendefinisikan fungsi `getJadwalHarian()` untuk mengambil jadwal harian.
    public function getJadwalHarian($lokasi = null, $tanggal = null)
    {
        // Mendapatkan tanggal hari ini.
        $today = date('Y-m-d');
        
        // Mendapatkan tanggal seminggu ke depan dari hari ini.
        $nextWeek = date('Y-m-d', strtotime('+6 days', strtotime($today)));

        // Memulai pembangunan query menggunakan Query Builder.
        $builder = $this->select('jadwal.*, mitra.*')
                        ->join('mitra', 'mitra.id = jadwal.mitra_id');

        // Jika lokasi tidak kosong, tambahkan kondisi ke lokasi.
        if (!empty($lokasi)) {
            $builder->where('mitra.lokasi', $lokasi);
        }

        // Jika tanggal tidak kosong, tambahkan kondisi ke tanggal.
        if (!empty($tanggal)) {
            $builder->where('jadwal.tanggal', $tanggal);
        }

        // Jika tidak ada filter, tampilkan semua data yang berada dalam rentang tanggal dari hari ini hingga seminggu ke depan.
        if (empty($lokasi) && empty($tanggal)) {
            return $builder
                    ->where('jadwal.tanggal >=', $today)
                    ->where('jadwal.tanggal <=', $nextWeek)
                    ->findAll();
        } else { // Jika ada filter, tampilkan data sesuai dengan filter yang diberikan.
            return $builder
                    ->where('jadwal.tanggal >=', $today)
                    ->where('jadwal.tanggal <=', $nextWeek)
                    ->findAll();
        }
    }

    // Mendefinisikan fungsi `getLokasi()` untuk mengambil data lokasi dari jadwal.
    public function getLokasi()
    {
        // Memulai pembangunan query menggunakan Query Builder untuk mendapatkan data jadwal beserta data mitra.
        return $this->select('jadwal.*, mitra.*')
                        ->join('mitra', 'mitra.id = jadwal.mitra_id')
                        ->findAll();
    }

    // Mendefinisikan fungsi `getJadwalWithMitra()` untuk mengambil jadwal dengan informasi mitra terkait.
    public function getJadwalWithMitra($user_id = null)
    {
        // Memulai pembangunan query menggunakan Query Builder.
        $builder = $this->db->table($this->table);
        $builder->select('jadwal.*, mitra.nama_fithub, mitra.lokasi, mitra.alamat_fithub');
        $builder->join('mitra', 'mitra.id = jadwal.mitra_id');

        // Jika user_id tidak kosong, tambahkan kondisi ke user_id.
        if ($user_id) {
            $builder->where('mitra.user_id', $user_id);
        }

        // Eksekusi query dan mengembalikan hasil dalam bentuk array.
        return $builder->get()->getResultArray();
    }
}

?>
