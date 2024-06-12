<?php

// Mendefinisikan namespace untuk controller ini.
namespace App\Controllers;

// Menggunakan model-model yang dibutuhkan.
use App\Models\Configs;
use App\Models\Jadwal;
use App\Models\Lokasi;
use App\Models\Member;
use App\Models\Membership;

// Mendefinisikan kelas Home yang merupakan turunan dari BaseController.
class Home extends BaseController
{
    // Mendefinisikan metode index, yang menangani logika untuk halaman utama.
    public function index()
    {
        // Membuat instance baru dari model Configs.
        $config = new Configs();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mengembalikan tampilan halaman landing dengan array data.
        return view('pages/landing', $data);
    }

    // Mendefinisikan metode membership, yang menangani logika untuk halaman membership.
    public function membership()
    {
        // Membuat instance baru dari model Configs dan Membership.
        $config = new Configs();
        $membership = new Membership();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mengambil data membership.
        $data['memberships'] = $membership->memberships();

        // Mengembalikan tampilan halaman membership dengan array data.
        return view('pages/membership', $data);
    }

    // Mendefinisikan metode registration, yang menangani logika untuk halaman registrasi berdasarkan ID paket.
    public function registration($id)
    {
        // Membuat instance baru dari model Configs dan Membership.
        $config = new Configs();
        $paketById = new Membership();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mengambil data paket membership berdasarkan ID.
        $data['paket'] = $paketById->find($id);

        // Mengembalikan tampilan halaman registrasi dengan array data.
        return view('pages/registration', $data);
    }

    // Mendefinisikan metode registerMember, yang menangani logika untuk mendaftarkan member baru.
    public function registerMember()
    {
        // Membuat instance baru dari model Member.
        $memberModel = new Member();

        // Mengambil data dari form pendaftaran.
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'telepon' => $this->request->getPost('telepon'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'id_paket_membership' => $this->request->getPost('id_paket_membership'),
        ];

        // Menyimpan data member baru ke dalam database.
        $memberModel->save($data);

        // Menyimpan pesan flash untuk notifikasi berhasil.
        session()->setFlashdata('message', 'Pendaftaran berhasil! admin akan mengirimkan anda pesan untuk mengkonfirmasi pembayaran.');

        // Mengarahkan ke halaman membership.
        return redirect()->to('/membership');
    }

    // Mendefinisikan metode jadwal, yang menangani logika untuk menampilkan jadwal berdasarkan lokasi dan tanggal.
    public function jadwal()
    {
        // Mengambil parameter lokasi dan tanggal dari URL.
        $lokasi = $this->request->getGet('lokasi');
        $tanggal = $this->request->getGet('tanggal');

        // Membuat instance baru dari model Configs dan Jadwal.
        $config = new Configs();
        $jadwalModel = new Jadwal();

        // Mengambil data jadwal harian berdasarkan lokasi dan tanggal.
        $data['jadwals'] = $jadwalModel->getJadwalHarian($lokasi, $tanggal);

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mengambil data lokasi tempat latihan.
        $data['tempat_latihan'] = $jadwalModel->getLokasi();

        // Menyimpan lokasi dan tanggal yang dipilih untuk ditampilkan kembali di form.
        $data['lokasi_terpilih'] = $this->request->getGet('lokasi');
        $data['tanggal_terpilih'] = $this->request->getGet('tanggal');

        // Mengembalikan tampilan halaman jadwal dengan array data.
        return view('pages/jadwal', $data);
    }

    // Mendefinisikan metode lokasi, yang menangani logika untuk menampilkan data lokasi.
    public function lokasi()
    {
        // Membuat instance baru dari model Configs dan Lokasi.
        $config = new Configs();
        $lokasiModel = new Lokasi();

        // Mengambil data lokasi tempat latihan.
        $data['tempat_latihan'] = $lokasiModel->getLokasiOnly();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mengembalikan tampilan halaman lokasi dengan array data.
        return view('pages/lokasi', $data);
    }
}
?>
