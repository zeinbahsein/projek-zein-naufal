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

// Mendefinisikan kelas MemberController yang merupakan turunan dari Controller
class MemberController extends Controller
{

    // Fungsi untuk membuat member baru
    public function create()
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi
        $data['userdata'] = session()->get();

        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance baru dari model Member
        $memberModels = new Member();

        // Menetapkan aturan validasi untuk form
        $validation = $this->validate([
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'id_paket_membership' => 'required',
            'id_mitra' => 'required',
            'telepon' => 'required',
            'tanggal_daftar' => 'required',
            'paket_berakhir' => 'required',
            'email' => 'required|valid_email'
        ]);

        // Memeriksa apakah validasi gagal
        if (!$validation) {
            // Jika ya, kembali ke halaman sebelumnya dengan pesan kesalahan
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Mengambil data dari form untuk disimpan ke database
        $data = [
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'id_paket_membership' => $this->request->getPost('id_paket_membership'),
            'id_mitra' => $this->request->getPost('id_mitra'),
            'telepon' => $this->request->getPost('telepon'),
            'tanggal_daftar' => $this->request->getPost('tanggal_daftar'),
            'paket_berakhir' => $this->request->getPost('paket_berakhir'),
            'email' => $this->request->getPost('email'),
        ];

        // Menyimpan data ke database dan menetapkan pesan sukses atau gagal
        if ($memberModels->insert($data)) {
            session()->setFlashdata('success', 'Member added successfully');
        } else {
            session()->setFlashdata('error', 'Failed to add Member');
        }

        // Mengarahkan pengguna ke halaman member
        return redirect()->to('/members');
    }

    // Fungsi untuk memperbarui data member
    public function updateMember($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi
        $data['userdata'] = session()->get();
        
        // Memuat helper untuk form dan URL
        helper(['form', 'url']);

        // Membuat instance baru dari model Member
        $memberModel = new Member();

        // Mengambil data dari form untuk diperbarui ke database
        $data = [
            'nama' => $this->request->getPost('nama'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'id_paket_membership' => $this->request->getPost('id_paket_membership'),
            'id_mitra' => $this->request->getPost('id_mitra'),
            'telepon' => $this->request->getPost('telepon'),
            'tanggal_daftar' => $this->request->getPost('tanggal_daftar'),
            'paket_berakhir' => $this->request->getPost('paket_berakhir'),
            'email' => $this->request->getPost('email'),
        ];

        // Memperbarui data di database dan menetapkan pesan sukses atau gagal
        if ($memberModel->update($id, $data)) {
            session()->setFlashdata('success', 'Member updated successfully');
        } else {
            session()->setFlashdata('error', 'Failed to update Member');
        }

        // Mengarahkan pengguna ke halaman member
        return redirect()->to('/members');
    }

    // Fungsi untuk menghapus data member
    public function delete($id)
    {
        // Memeriksa apakah pengguna sudah login
        if (!session()->get('logged_in')) {
            // Jika tidak, arahkan ke halaman login
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi
        $data['userdata'] = session()->get();

        // Membuat instance baru dari model Member
        $memberModel = new Member();

        // Memeriksa apakah member dengan ID tersebut ada
        if ($memberModel->find($id)) {
            // Jika ya, hapus member dan menetapkan pesan sukses
            $memberModel->delete($id);
            session()->setFlashdata('success', 'Member deleted successfully');
        } else {
            // Jika tidak, menetapkan pesan gagal
            session()->setFlashdata('error', 'Failed to delete Member');
        }

        // Mengarahkan pengguna ke halaman member
        return redirect()->to('/members');
    }
}
