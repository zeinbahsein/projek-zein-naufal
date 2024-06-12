<?php

// Mendefinisikan namespace untuk controller ini
namespace App\Controllers;

// Mengimpor model yang diperlukan
use App\Models\Jadwal;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Mitra;
use App\Models\User;
use App\Models\UserModel;
use CodeIgniter\Controller;

// Mendefinisikan kelas PaketController yang merupakan turunan dari Controller
class PaketController extends Controller
{

    // Fungsi untuk membuat paket keanggotaan baru
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
        if($role != 'superadmin'){
            return redirect()->to('/dashboard');
        }

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance baru dari model Membership
        $membershipModel = new Membership();

        // Menyiapkan data yang akan disimpan
        $validation = $this->validate([
            'jangka_waktu' => 'required',
            'biaya_bulanan' => 'required',
            'biaya_total' => 'required',
            'keunggulan' => 'permit_empty'
        ]);

        // Memeriksa apakah validasi gagal
        if (!$validation) {
            // Jika ya, kembali ke halaman sebelumnya dengan pesan kesalahan
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data yang akan disimpan
        $data = [
            'jangka_waktu' => $this->request->getPost('jangka_waktu'),
            'biaya_bulanan' => $this->request->getPost('biaya_bulanan'),
            'biaya_total' => $this->request->getPost('biaya_total'),
            'keunggulan' => $this->request->getPost('keunggulan'),
        ];

        // Menyimpan data ke database dan menetapkan pesan sukses atau gagal
        if ($membershipModel->insert($data)) {
            session()->setFlashdata('success', 'Plan added successfully');
        } else {
            session()->setFlashdata('error', 'Failed to add Plan');
        }

        // Mengarahkan pengguna ke halaman pricing
        return redirect()->to('/pricing');
    }

    // Fungsi untuk memperbarui paket keanggotaan
    public function update($id)
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
        if($role != 'superadmin'){
            return redirect()->to('/dashboard');
        }

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Load UserModel
        $membershipModel = new Membership();

        // Validasi input
        $rules = [
            'jangka_waktu' => 'required',
            'biaya_bulanan' => 'required',
            'biaya_total' => 'required',
            'keunggulan' => 'permit_empty'
        ];

        // Memvalidasi aturan
        if ($this->validate($rules)) {
            // Jika validasi berhasil, ambil data dari form untuk diperbarui ke database
            $data = [
                'jangka_waktu' => $this->request->getPost('jangka_waktu'),
                'biaya_bulanan' => $this->request->getPost('biaya_bulanan'),
                'biaya_total' => $this->request->getPost('biaya_total'),
                'keunggulan' => $this->request->getPost('keunggulan'),
            ];

            // Memperbarui data di database dan menetapkan pesan sukses
            $membershipModel->update($id, $data);
            session()->setFlashdata('success', 'Plan updated successfully');
            return redirect()->to('/pricing');
        } else {
            // Jika validasi gagal, tetapkan pesan kesalahan dan kembali ke halaman sebelumnya
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
    }

    // Fungsi untuk menghapus paket keanggotaan
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

        // Membuat instance model Membership dan Member
        $pricingModel = new Membership();
        $memberModel = new Member();

        // Memeriksa apakah paket dengan ID tertentu ditemukan
        if ($pricingModel->find($id)) {
            // Jika ditemukan, ambil semua anggota yang menggunakan paket tersebut
            $members = $memberModel->where('id_paket_membership', $id)->findAll();
            // Jika ada anggota yang menggunakan paket tersebut, hapus mereka
            if ($members) {
                $memberModel->where('id_paket_membership', $id)->delete();
            }
            
            // Hapus paket dari database
            $pricingModel->delete($id);

            session()->setFlashdata('success', 'Plan deleted successfully');
        } else {
            // Jika paket tidak ditemukan, tetapkan pesan kesalahan
            session()->setFlashdata('error', 'Failed to delete Plan');
        }

        // Mengarahkan pengguna ke halaman pricing
        return redirect()->to('/pricing');
    }
}
