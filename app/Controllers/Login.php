<?php

namespace App\Controllers;

use App\Models\Configs;
use App\Models\User;

class Login extends BaseController
{
    public function index()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        $config = new Configs();
        $data['configs'] = $config->dataConfig();
		return view('pages/login', $data);
    }

    public function login()
    {
        // Validasi form
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if ($this->validate($rules)) {
            // Ambil data dari form
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $userModel = new User();
            $user = $userModel->where('username', $username)
                              ->first();

            if ($user) {
                if ($password == $user['password']) {
                    $sessionData = [
                        'id' => $user['id'],
                        'username' => $user['username'],
                        'role' => $user['role'],
                        'logged_in' => true
                    ];

                    session()->set($sessionData);

                    return redirect()->to('/dashboard');
                }
            }

            session()->setFlashdata('message', 'username atau password salah!');
        }

        $config = new Configs();
        $data['configs'] = $config->dataConfig();
        return view('pages/login', $data);
    }

    

    
}
