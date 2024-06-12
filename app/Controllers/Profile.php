<?php

// Mendefinisikan namespace untuk controller ini
namespace App\Controllers;

// Mengimpor model yang diperlukan
use App\Models\Mitra;
use App\Models\User;
use App\Models\UserModel;
use CodeIgniter\Controller;

// Mendefinisikan kelas Profile yang merupakan turunan dari Controller
class Profile extends Controller
{
    // Fungsi untuk memperbarui profil pengguna
    public function update($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance model User
        $userModel = new User();

        // Validasi input
        $rules = [
            'username' => 'required|min_length[3]|max_length[20]',
            'password' => 'permit_empty|min_length[3]'
        ];

        // Memvalidasi aturan
        if ($this->validate($rules)) {
            // Jika validasi berhasil, ambil data dari form untuk diperbarui ke database
            $data = [
                'username' => $this->request->getPost('username'),
            ];

            // Periksa apakah kata sandi disediakan
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                $data['password'] = $password;
            }

            // Memperbarui data pengguna di database
            $userModel->update($id, $data);

            // Mengambil data pengguna yang diperbarui
            $updatedUser = $userModel->find($id);

            // Memperbarui data sesi pengguna
            $sessionData = [
                'id' => $updatedUser['id'],
                'username' => $updatedUser['username'],
                'role' => $updatedUser['role'],
                'logged_in' => true
            ];
            session()->set($sessionData);

            // Menetapkan pesan sukses dan mengalihkan
            session()->setFlashdata('success', 'Profile updated successfully');
            return redirect()->to('/profile');
        } else {
            // Menetapkan pesan kesalahan dan kembali ke halaman profil
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
    }

    // Fungsi untuk memperbarui profil Mitra
    public function updateMitra($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance model Mitra
        $mitraModel = new Mitra();

        // Menyiapkan data untuk diperbarui
        $data = [
            'user_id' => $this->request->getPost('user_id'),
            'nama_fithub' => $this->request->getPost('nama_fithub'),
            'nama_pemilik' => $this->request->getPost('nama_pemilik'),
            'lokasi' => $this->request->getPost('lokasi'),
            'telepon' => $this->request->getPost('telepon'),
            'alamat_fithub' => $this->request->getPost('alamat_fithub'),
            'email' => $this->request->getPost('email'),
        ];

        // Memperbarui data di database
        if ($mitraModel->update($id, $data)) {
            // Menetapkan pesan sukses jika berhasil
            session()->setFlashdata('success', 'Profile Mitra updated successfully');
        } else {
            // Menetapkan pesan kesalahan jika gagal
            session()->setFlashdata('error', 'Failed to update Profile Mitra');
        }

        // Mengarahkan pengguna ke halaman profil
        return redirect()->to('/profile');
    }
}
