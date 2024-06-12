<?php

namespace App\Controllers;

use App\Models\Configs;
use App\Models\Jadwal;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Mitra;
use App\Models\User;

// Mendefinisikan kelas Dashboard yang merupakan turunan dari BaseController.
class Dashboard extends BaseController
{
    // Mendefinisikan metode index, yang menangani logika untuk halaman utama dashboard.
    public function index()
    {
        // Memeriksa apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Menetapkan judul halaman untuk dashboard.
        $data['title'] = 'Dashboard';

        // Membuat instance baru dari model Configs.
        $config = new Configs();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mendapatkan data pengguna dari sesi.
        $data['userdata'] = session()->get();

        // Mengembalikan tampilan dashboard dengan array data.
        return view('pages/dashboard', $data);
    }

    // Mendefinisikan metode logout.
    public function logout()
    {
        // Menghancurkan sesi untuk logout pengguna.
        session()->destroy();

        // Mengarahkan ke halaman login.
        return redirect()->to('/login');
    }

    // Mendefinisikan metode profile, yang menangani logika profil pengguna.
    public function profile()
    {
        // Memeriksa apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Menetapkan judul halaman untuk profil.
        $data['title'] = 'Settings';

        // Membuat instance baru dari model Configs.
        $config = new Configs();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mendapatkan data pengguna dari sesi.
        $data['userdata'] = session()->get();

        // Memeriksa peran pengguna. Jika pengguna adalah 'mitra', ambil data khusus untuk 'mitra'.
        $role = $data['userdata']['role'];
        if ($role == 'mitra') {
            $id_user = $data['userdata']['id'];
            $mitra = new Mitra();
            $data['data_mitras'] = $mitra->dataMitra($id_user);
        }

        // Mengembalikan tampilan profil dengan array data.
        return view('pages/profile', $data);
    }

    // Mendefinisikan metode mitra, yang menangani logika untuk menampilkan data 'mitra'.
    public function mitra()
    {
        // Memeriksa apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi.
        $data['userdata'] = session()->get();

        // Memeriksa peran pengguna. Jika pengguna bukan 'superadmin', arahkan ke dashboard.
        $role = $data['userdata']['role'];
        if($role != 'superadmin'){
            return redirect()->to('/dashboard');
        }

        // Membuat instance baru dari model Mitra, Configs, dan User.
        $mitraModel = new Mitra();
        $config = new Configs();
        $userModel = new User();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mengambil semua data 'mitra'.
        $data['data_mitras'] = $mitraModel->fetchAllMitra();

        // Mengambil semua data pengguna.
        $data['users'] = $userModel->user();

        // Menetapkan judul halaman untuk halaman 'mitra'.
        $data['title'] = 'Data Mitra';

        // Mengembalikan tampilan 'mitra' dengan array data.
        return view('pages/mitra', $data);
    }

    // Mendefinisikan metode member, yang menangani logika untuk menampilkan data member.
    public function member()
    {
        // Memeriksa apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi.
        $data['userdata'] = session()->get();
        $role = $data['userdata']['role'];
        $userId = $data['userdata']['id'];

        // Membuat instance baru dari model Mitra, Configs, Member, Membership, dan User.
        $mitraModel = new Mitra();
        $config = new Configs();
        $members = new Member();
        $paket = new Membership();
        $user = new User();
        
        // Mengambil semua paket membership.
        $data['pakets'] = $paket->findAll();

        // Mengambil semua data pengguna.
        $data['users'] = $user->findAll();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Mengambil semua data 'mitra'.
        $data['data_mitras'] = $mitraModel->fetchAllMitra();

        // Menetapkan judul halaman untuk halaman member.
        $data['title'] = 'Data Members';

        // Mengambil member dengan detail. Jika pengguna adalah 'superadmin', ambil semua member.
        // Jika tidak, ambil member khusus untuk ID pengguna.
        if ($role == 'superadmin') {
            $data['members'] = $members->getMembersWithDetails();
        } else {
            $data['members'] = $members->getMembersWithDetails($userId);
        }
        
        // Mengembalikan tampilan members dengan array data.
        return view('pages/members', $data);
    }

    // Mendefinisikan metode user, yang menangani logika untuk menampilkan data pengguna.
    public function user()
    {
        // Memeriksa apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi.
        $data['userdata'] = session()->get();

        // Memeriksa peran pengguna. Jika pengguna bukan 'superadmin', arahkan ke dashboard.
        $role = $data['userdata']['role'];
        if($role != 'superadmin'){
            return redirect()->to('/dashboard');
        }
        
        // Membuat instance baru dari model Configs dan User.
        $config = new Configs();
        $user = new User();
        
        // Mengambil semua data pengguna.
        $data['users'] = $user->findAll();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Menetapkan judul halaman untuk halaman pengguna.
        $data['title'] = 'Data Pengguna';

        // Mengembalikan tampilan users dengan array data.
        return view('pages/users', $data);
    }

    // Mendefinisikan metode paket, yang menangani logika untuk menampilkan data paket membership.
    public function paket()
    {
        // Memeriksa apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi.
        $data['userdata'] = session()->get();

        // Memeriksa peran pengguna. Jika pengguna bukan 'superadmin', arahkan ke dashboard.
        $role = $data['userdata']['role'];
        if($role != 'superadmin'){
            return redirect()->to('/dashboard');
        }
        
        // Membuat instance baru dari model Configs dan Membership.
        $config = new Configs();
        $paket = new Membership();
        
        // Mengambil semua data paket membership.
        $data['memberships'] = $paket->findAll();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Menetapkan judul halaman untuk halaman pricing.
        $data['title'] = 'Pricing';

        // Mengembalikan tampilan pricing dengan array data.
        return view('pages/pricing', $data);
    }

    // Mendefinisikan metode program, yang menangani logika untuk menampilkan data jadwal program.
    public function program()
    {
        // Memeriksa apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi.
        $data['userdata'] = session()->get();
        $user_id = $data['userdata']['id'];
        $role = $data['userdata']['role'];

        // Membuat instance baru dari model Jadwal, Mitra, dan Configs.
        $jadwalModel = new Jadwal();
        $mitraModel = new Mitra();
        $config = new Configs();

        // Mengambil data 'mitra' yang terkait dengan ID pengguna.
        $data['data_mitra'] = $mitraModel->where('user_id', $user_id)->findAll();

        // Mengambil data jadwal program. Jika pengguna adalah 'superadmin', ambil semua jadwal.
        // Jika tidak, ambil jadwal khusus untuk ID pengguna.
        if ($role == 'superadmin') {
            $jadwalData = $jadwalModel->getJadwalWithMitra();
        } else {
            $jadwalData = $jadwalModel->getJadwalWithMitra($user_id);
        }

        // Mengambil data jadwal.
        $data['jadwals'] = $jadwalData;

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Menetapkan judul halaman untuk halaman program.
        $data['title'] = 'Data Jadwal';

        // Mengembalikan tampilan program dengan array data.
        return view('pages/program', $data);
    }

    // Mendefinisikan metode setting, yang menangani logika untuk menampilkan data pengaturan.
    public function setting()
    {
        // Memeriksa apakah pengguna sudah login. Jika tidak, arahkan ke halaman login.
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Mendapatkan data pengguna dari sesi.
        $data['userdata'] = session()->get();

        // Memeriksa peran pengguna. Jika pengguna bukan 'superadmin', arahkan ke dashboard.
        $role = $data['userdata']['role'];
        if($role != 'superadmin'){
            return redirect()->to('/dashboard');
        }
        
        // Membuat instance baru dari model Configs.
        $config = new Configs();

        // Mengambil data konfigurasi.
        $data['configs'] = $config->dataConfig();

        // Menetapkan judul halaman untuk halaman pengaturan.
        $data['title'] = 'Settings';

        // Mengembalikan tampilan pengaturan dengan array data.
        return view('pages/settings', $data);
    }
}
?>
