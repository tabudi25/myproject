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

                // Check if user is admin and redirect accordingly
                if ($user['role'] === 'admin') {
                    // Clear any redirect URL for admin users
                    $session->remove('redirect_url');
                    return redirect()->to('/fluffy-admin')->with('msg', 'Welcome to Admin Dashboard!');
                } else {
                    // Get the redirect URL from session, or default to petshop
                    $redirectUrl = $session->get('redirect_url') ?: '/petshop';
                    
                    // Clear the redirect URL from session
                    $session->remove('redirect_url');

                    return redirect()->to($redirectUrl)->with('msg', 'Welcome back!');
                }
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

        // Validate input
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name'     => 'required|min_length[2]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[customer,admin,staff]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('msg', 'Validation failed: ' . implode(', ', $validation->getErrors()));
        }

        // Get form data
        $name = trim($this->request->getVar('name'));
        $email = trim($this->request->getVar('email'));
        $password = $this->request->getVar('password');
        $role = $this->request->getVar('role');

        // Check if name is empty after trimming
        if (empty($name)) {
            return redirect()->back()->withInput()->with('msg', 'Name cannot be empty');
        }

        $data = [
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role'     => $role
        ];

        try {
            if ($userModel->save($data)) {
                return redirect()->to('/login')->with('msg', 'Account created successfully!');
            } else {
                return redirect()->back()->withInput()->with('msg', 'Failed to create account. Please try again.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('msg', 'Error: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
