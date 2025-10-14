<?php

namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('fluffy-planet/login'); // same folder
    }

    public function loginAuth()
    {
        $session = session();
        $userModel = new UserModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $userModel->where('email', $email)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $session->set([
                    'user_id'    => $user['id'],
                    'user_name'  => $user['name'],
                    'email'      => $user['email'],
                    'role'       => $user['role'],
                    'isLoggedIn' => true
                ]);

                return redirect()->to('petshop'); // redirect to your page
            } else {
                return redirect()->back()->with('msg', 'Wrong password');
            }
        } else {
            return redirect()->back()->with('msg', 'Email not found');
        }
    }

    public function signup()
    {
        return view('fluffy-planet/signup'); // same folder
    }

    public function register()
    {
        $userModel = new UserModel();

        $data = [
            'name'     => $this->request->getVar('name'),
            'email'    => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getVar('role')
        ];

        $userModel->save($data);
        return redirect()->to('/login')->with('msg', 'Account created!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
