<?php

// Mendefinisikan namespace untuk controller ini
namespace App\Controllers;

// Mengimpor model yang diperlukan
use App\Models\Configs;
use App\Models\Mitra;
use App\Models\User;
use App\Models\UserModel;
use CodeIgniter\Controller;

// Mendefinisikan kelas SettingController yang merupakan turunan dari Controller
class SettingController extends Controller
{
    // Fungsi untuk memperbarui pengaturan
    public function update($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance model Configs
        $configModel = new Configs();

        // Menyiapkan aturan validasi
        $rules = [
            'nama_aplikasi' => 'required',
            'nama_perusahaan' => 'required',
            'deskripsi_aplikasi' => 'required',
            'alamat' => 'required',
            'whatsapp' => 'required',
            'telepon' => 'required',
            'email' => 'required',
            'instagram' => 'permit_empty',
            'tiktok' => 'permit_empty',
            'facebook' => 'permit_empty',
        ];

        // Memvalidasi data
        if ($this->validate($rules)) {
            // Menyiapkan data yang akan diperbarui
            $data = [
                'nama_aplikasi' => $this->request->getPost('nama_aplikasi'),
                'nama_perusahaan' => $this->request->getPost('nama_perusahaan'),
                'deskripsi_aplikasi' => $this->request->getPost('deskripsi_aplikasi'),
                'alamat' => $this->request->getPost('alamat'),
                'whatsapp' => $this->request->getPost('whatsapp'),
                'telepon' => $this->request->getPost('telepon'),
                'email' => $this->request->getPost('email'),
                'instagram' => $this->request->getPost('instagram'),
                'tiktok' => $this->request->getPost('tiktok'),
                'facebook' => $this->request->getPost('facebook'),
            ];

            // Memperbarui data pengaturan
            $configModel->update($id, $data);

            // Menetapkan pesan sukses dan mengarahkan kembali ke halaman pengaturan
            session()->setFlashdata('success', 'Settings updated successfully');
            return redirect()->to('/settings');
        } else {
            // Menetapkan pesan kesalahan dan mengarahkan kembali ke halaman pengaturan dengan data input sebelumnya
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
    }
}
