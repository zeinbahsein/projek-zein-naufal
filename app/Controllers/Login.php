<?php

// Mendefinisikan namespace untuk controller ini.
namespace App\Controllers;

// Menggunakan model-model yang dibutuhkan.
use App\Models\Configs;
use App\Models\User;

// Mendefinisikan kelas Login yang merupakan turunan dari BaseController.
class Login extends BaseController
{
    // Mendefinisikan metode index, yang menangani logika untuk halaman login.
    public function index()
    {
        // Memeriksa apakah pengguna sudah login. Jika ya, arahkan ke dashboard.
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        // Membuat instance baru dari model Configs.
        $config = new Configs();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mengembalikan tampilan halaman login dengan array data.
        return view('pages/login', $data);
    }

    // Mendefinisikan metode login, yang menangani logika untuk proses login.
    public function login()
    {
        // Menetapkan aturan validasi untuk form login.
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        // Memeriksa apakah form valid sesuai dengan aturan.
        if ($this->validate($rules)) {
            // Mengambil data dari form login.
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // Membuat instance baru dari model User.
            $userModel = new User();

            // Mencari pengguna berdasarkan username.
            $user = $userModel->where('username', $username)->first();

            // Memeriksa apakah pengguna ditemukan.
            if ($user) {
                // Memeriksa apakah password yang dimasukkan sesuai.
                if ($password == $user['password']) {
                    // Menyiapkan data sesi pengguna.
                    $sessionData = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role'],
                        'logged_in' => true
                    ];

                    // Menyimpan data sesi pengguna.
                    session()->set($sessionData);

                    // Mengarahkan pengguna ke dashboard.
                    return redirect()->to('/dashboard');
                }
            }

            // Menyimpan pesan flash untuk notifikasi kesalahan.
            session()->setFlashdata('message', 'username atau password salah!');
        }

        // Membuat instance baru dari model Configs.
        $config = new Configs();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mengembalikan tampilan halaman login dengan array data.
        return view('pages/login', $data);
    }
}
?>
