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

// Mendefinisikan kelas UserController yang merupakan turunan dari Controller
class UserController extends Controller
{

    // Fungsi untuk membuat pengguna baru
    public function create()
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mengambil data pengguna yang sedang masuk
        $data['userdata'] = session()->get();

        // Memeriksa peran pengguna
        $role = $data['userdata']['role'];
        if($role != 'superadmin'){
            // Jika bukan superadmin, arahkan ke dasbor
            return redirect()->to('/dashboard');
        }

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance model User
        $userModels = new User();

        // Menyiapkan data untuk disimpan
        $validation = $this->validate([
            'username' => 'required',
            'password' => 'required',
            'role' => 'required'
        ]);

        if (!$validation) {
            // Jika validasi gagal, kembali ke halaman sebelumnya dengan data input
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data yang akan disimpan
        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role' => $this->request->getPost('role'),
        ];

        // Memperbarui rekaman
        if ($userModels->insert($data)) {
            session()->setFlashdata('success', 'User added successfully');
        } else {
            session()->setFlashdata('error', 'Failed to add User');
        }

        // Redirect ke halaman /users
        return redirect()->to('/users');
    }

    // Fungsi untuk memperbarui informasi pengguna
    public function update($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mengambil data pengguna yang sedang masuk
        $data['userdata'] = session()->get();
        // Memeriksa peran pengguna
        $role = $data['userdata']['role'];
        if($role != 'superadmin'){
            // Jika bukan superadmin, arahkan ke dasbor
            return redirect()->to('/dashboard');
        }

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Memuat model User
        $userModel = new User();

        // Validasi input
        $rules = [
            'username' => 'required|min_length[3]|max_length[20]',
            'password' => 'permit_empty|min_length[3]',
            'role' => 'required'
        ];

        if ($this->validate($rules)) {
            // Menyiapkan data yang akan diperbarui
            $data = [
                'username' => $this->request->getPost('username'),
                'role' => $this->request->getPost('role'),
            ];

            // Memeriksa apakah password disediakan
            $password = $this->request->getPost('password');
            if (!empty($password)) {
                $data['password'] = $password;
            }

            // Memperbarui data pengguna
            $userModel->update($id, $data);

            // Menetapkan pesan sukses dan mengalihkan
            session()->setFlashdata('success', 'Account updated successfully');
            return redirect()->to('/users');
        } else {
            // Menetapkan pesan kesalahan dan mengalihkan kembali ke halaman profil dengan data input sebelumnya
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
    }

    // Fungsi untuk menghapus pengguna
    public function delete($id)
    {

        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mengambil data pengguna yang sedang masuk
        $data['userdata'] = session()->get();
        // Memeriksa peran pengguna
        $role = $data['userdata']['role'];
        if ($role != 'superadmin') {
            // Jika bukan superadmin, arahkan ke dasbor
            return redirect()->to('/dashboard');
        }

        // Memuat model User, Mitra, Jadwal, dan Member
        $userModel = new User();
        $mitraModel = new Mitra();
        $jadwalModel = new Jadwal();
        $memberModel = new Member();

        if ($userModel->find($id)) {
            // Ambil semua data mitra yang user_id-nya sama dengan $id
            $mitras = $mitraModel->where('user_id', $id)->findAll();
            $members = $memberModel->where('id_mitra', $id)->findAll();
            if($members){
                $memberModel->where('id_mitra', $id)->delete();
            }

            if ($mitras) {
                foreach ($mitras as $mitra) {
                    // Hapus semua jadwal yang terhubung dengan data mitra
                    $jadwalModel->where('mitra_id', $mitra['id'])->delete();
                }
                // Hapus data mitra yang user_id-nya sama dengan $id
                $mitraModel->where('user_id', $id)->delete();
            }

            // Hapus user & members
            $userModel->delete($id);

            session()->setFlashdata('success', 'Account deleted successfully');
        } else {
            session()->setFlashdata('error', 'Failed to delete Account');
        }

        // Redirect ke halaman /users
        return redirect()->to('/users');
    }
}
