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

// Mendefinisikan kelas MitraController yang merupakan turunan dari Controller
class MitraController extends Controller
{

    // Fungsi untuk membuat mitra baru
    public function create()
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi
        $data['userdata'] = session()->get();
        
        // Mendapatkan peran (role) pengguna
        $role = $data['userdata']['role'];
        
        // Jika peran pengguna bukan 'superadmin', arahkan ke halaman dashboard
        if ($role != 'superadmin') {
            return redirect()->to('/dashboard');
        }

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance baru dari model Mitra
        $mitraModel = new Mitra();

        // Menetapkan aturan validasi untuk form
        $validation = $this->validate([
            'user_id' => 'required',
            'nama_fithub' => 'required',
            'nama_pemilik' => 'required',
            'lokasi' => 'required',
            'telepon' => 'required',
            'alamat_fithub' => 'required',
            'email' => 'required|valid_email'
        ]);

        // Memeriksa apakah validasi gagal
        if (!$validation) {
            // Jika ya, kembali ke halaman sebelumnya dengan pesan kesalahan
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data yang akan disimpan
        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'nama_fithub' => $this->request->getPost('nama_fithub'),
            'nama_pemilik' => $this->request->getPost('nama_pemilik'),
            'lokasi' => $this->request->getPost('lokasi'),
            'telepon' => $this->request->getPost('telepon'),
            'alamat_fithub' => $this->request->getPost('alamat_fithub'),
            'email' => $this->request->getPost('email'),
        ];

        // Menyimpan data ke database dan menetapkan pesan sukses atau gagal
        if ($mitraModel->insert($data)) {
            session()->setFlashdata('success', 'Mitra added successfully');
        } else {
            session()->setFlashdata('error', 'Failed to add Mitra');
        }

        // Mengarahkan pengguna ke halaman mitra
        return redirect()->to('/mitra');
    }

    // Fungsi untuk memperbarui data mitra
    public function updateMitra($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi
        $data['userdata'] = session()->get();
        
        // Mendapatkan peran (role) pengguna
        $role = $data['userdata']['role'];
        
        // Jika peran pengguna bukan 'superadmin', arahkan ke halaman dashboard
        if ($role != 'superadmin') {
            return redirect()->to('/dashboard');
        }

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance baru dari model Mitra
        $mitraModel = new Mitra();

        // Mendapatkan data dari form untuk diperbarui ke database
        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'nama_fithub' => $this->request->getPost('nama_fithub'),
            'nama_pemilik' => $this->request->getPost('nama_pemilik'),
            'lokasi' => $this->request->getPost('lokasi'),
            'telepon' => $this->request->getPost('telepon'),
            'alamat_fithub' => $this->request->getPost('alamat_fithub'),
            'email' => $this->request->getPost('email'),
        ];

        // Memperbarui data di database dan menetapkan pesan sukses atau gagal
        if ($mitraModel->update($id, $data)) {
            session()->setFlashdata('success', 'Mitra updated successfully');
        } else {
            session()->setFlashdata('error', 'Failed to update Mitra');
        }

        // Mengarahkan pengguna ke halaman mitra
        return redirect()->to('/mitra');
    }

    // Fungsi untuk menghapus data mitra
    public function delete($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi
        $data['userdata'] = session()->get();
        
        // Mendapatkan peran (role) pengguna
        $role = $data['userdata']['role'];
        
        // Jika peran pengguna bukan 'superadmin', arahkan ke halaman dashboard
        if ($role != 'superadmin') {
            return redirect()->to('/dashboard');
        }

        // Membuat instance baru dari model Mitra
        $mitraModel = new Mitra();

        // Memeriksa apakah mitra dengan ID tersebut ada
        if ($mitraModel->find($id)) {
            // Jika ya, hapus mitra

            // Membuat instance baru dari model Jadwal
            $jadwalModel = new Jadwal();
            // Mencari data jadwal yang id_mitranya = $id
            $jadwal = $jadwalModel->where('mitra_id', $id)->findAll();
            // Jika ditemukan, hapus data jadwal
            if ($jadwal) {
                $jadwalModel->where('mitra_id', $id)->delete();
            }

            // Membuat instance baru dari model Member
            $memberModel = new Member();
            // Mencari data member yang id_mitranya = $id
            $members = $memberModel->where('id_mitra', $id)->findAll();
            // Jika ditemukan, kosongkan nilai id_mitranya
            if ($members) {
                $memberModel->where('id_mitra', $id)->set(['id_mitra' => null])->update();
            }

            // Hapus mitra dan tetapkan pesan sukses
            $mitraModel->delete($id);
            session()->setFlashdata('success', 'Mitra deleted successfully');
        } else {
            // Jika tidak, tetapkan pesan gagal
            session()->setFlashdata('error', 'Failed to delete Mitra');
        }

        // Mengarahkan pengguna ke halaman mitra
        return redirect()->to('/mitra');
    }
}
