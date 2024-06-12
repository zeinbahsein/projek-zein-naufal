<?php

// Mendefinisikan namespace untuk controller ini
namespace App\Controllers;

// Mengimpor model yang diperlukan
use App\Models\Jadwal;
use App\Models\Member;
use App\Models\Mitra;
use App\Models\User;
use App\Models\UserModel;
use CodeIgniter\Controller;

// Mendefinisikan kelas ProgramController yang merupakan turunan dari Controller
class ProgramController extends Controller
{

    // Fungsi untuk menambahkan program baru
    public function create()
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mengambil data pengguna yang login
        $data['userdata'] = session()->get();

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance model Jadwal
        $jadwalModel = new Jadwal();

        // Menyiapkan data untuk disimpan
        $validation = $this->validate([
            'mitra_id' => 'required',
            'nama_latihan' => 'required',
            'trainer' => 'required',
            'jam_kegiatan' => 'required',
            'level_kesulitan' => 'required',
            'durasi_latihan' => 'required',
            'tanggal' => 'required',
        ]);

        // Memvalidasi data
        if (!$validation) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan pesan kesalahan dan data input sebelumnya
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data yang akan disimpan
        $data = [
            'mitra_id' => $this->request->getPost('mitra_id'),
            'nama_latihan' => $this->request->getPost('nama_latihan'),
            'trainer' => $this->request->getPost('trainer'),
            'jam_kegiatan' => $this->request->getPost('jam_kegiatan'),
            'level_kesulitan' => $this->request->getPost('level_kesulitan'),
            'durasi_latihan' => $this->request->getPost('durasi_latihan'),
            'tanggal' => $this->request->getPost('tanggal'),
        ];

        // Menyimpan data ke database
        if ($jadwalModel->insert($data)) {
            // Menetapkan pesan sukses jika berhasil
            session()->setFlashdata('success', 'Program added successfully');
        } else {
            // Menetapkan pesan kesalahan jika gagal
            session()->setFlashdata('error', 'Failed to add Program');
        }

        // Mengarahkan pengguna kembali ke halaman program
        return redirect()->to('/program');
    }

    // Fungsi untuk memperbarui program
    public function update($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mengambil data pengguna yang login
        $data['userdata'] = session()->get();

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance model Jadwal
        $jadwalModel = new Jadwal();

        // Menyiapkan data untuk diperbarui
        $data = [
            'mitra_id' => $this->request->getPost('mitra_id'),
            'nama_latihan' => $this->request->getPost('nama_latihan'),
            'trainer' => $this->request->getPost('trainer'),
            'jam_kegiatan' => $this->request->getPost('jam_kegiatan'),
            'level_kesulitan' => $this->request->getPost('level_kesulitan'),
            'durasi_latihan' => $this->request->getPost('durasi_latihan'),
            'tanggal' => $this->request->getPost('tanggal'),
        ];

        // Memperbarui data di database
        if ($jadwalModel->update($id, $data)) {
            // Menetapkan pesan sukses jika berhasil
            session()->setFlashdata('success', 'Program updated successfully');
        } else {
            // Menetapkan pesan kesalahan jika gagal
            session()->setFlashdata('error', 'Failed to update Program');
        }

        // Mengarahkan pengguna kembali ke halaman program
        return redirect()->to('/program');
    }

    // Fungsi untuk menghapus program
    public function delete($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mengambil data pengguna yang login
        $data['userdata'] = session()->get();

        // Membuat instance model Jadwal
        $jadwalModel = new Jadwal();

        // Menghapus data program dari database
        if ($jadwalModel->delete($id)) { 
            // Menetapkan pesan sukses jika berhasil
            session()->setFlashdata('success', 'Program deleted successfully');
        } else {
            // Menetapkan pesan kesalahan jika gagal
            session()->setFlashdata('error', 'Failed to delete Program');
        }

        // Mengarahkan pengguna kembali ke halaman program
        return redirect()->to('/program');
    }
}
