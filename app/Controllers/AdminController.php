<?php

namespace App\Controllers;

use App\Models\AnimalModel;
use App\Models\UserModel;

class AdminController extends BaseController
{
    protected $animalModel;
    protected $userModel;

    public function __construct()
    {
        $this->animalModel = new AnimalModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to('/login')->with('msg', 'Admin access required.');
        }

        return view('fluffy-planet/fluffy-admin');
    }

    public function getAnimals()
    {
        // API endpoint to get all animals as JSON
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $animals = $this->animalModel->findAll();
        return $this->response->setJSON($animals);
    }

    public function createAnimal()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        // Validate input
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name'     => 'required|min_length[2]|max_length[255]',
            'category' => 'required|in_list[cat,dog,bird,hamster,rabbit,fish]',
            'age'      => 'required|integer|greater_than[0]',
            'gender'   => 'required|in_list[male,female]',
            'price'    => 'required|decimal|greater_than[0]',
            'description' => 'max_length[1000]',
            'image'    => 'uploaded[image]|is_image[image]|max_size[image,2048]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ])->setStatusCode(400);
        }

        // Handle image upload
        $imageFile = $this->request->getFile('image');
        $imageName = null;

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $imageName = $imageFile->getRandomName();
            $imageFile->move(ROOTPATH . 'public/uploads', $imageName);
        }

        // Prepare data
        $data = [
            'name'        => $this->request->getPost('name'),
            'category'    => $this->request->getPost('category'),
            'age'         => (int)$this->request->getPost('age'),
            'gender'      => $this->request->getPost('gender'),
            'price'       => (float)$this->request->getPost('price'),
            'description' => $this->request->getPost('description') ?: '',
            'image'       => $imageName,
            'status'      => 'available',
            'added_by'    => session()->get('user_id')
        ];

        try {
            if ($this->animalModel->save($data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Animal added successfully!',
                    'data' => $data
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to add animal.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function updateAnimal($id = null)
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$id) {
            return $this->response->setJSON(['error' => 'Animal ID required'])->setStatusCode(400);
        }

        $animal = $this->animalModel->find($id);
        if (!$animal) {
            return $this->response->setJSON(['error' => 'Animal not found'])->setStatusCode(404);
        }

        // Validate input
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name'     => 'required|min_length[2]|max_length[255]',
            'category' => 'required|in_list[cat,dog,bird,hamster,rabbit,fish]',
            'age'      => 'required|integer|greater_than[0]',
            'gender'   => 'required|in_list[male,female]',
            'price'    => 'required|decimal|greater_than[0]',
            'description' => 'max_length[1000]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ])->setStatusCode(400);
        }

        // Handle image upload if provided
        $imageFile = $this->request->getFile('image');
        $imageName = $animal['image']; // Keep existing image by default

        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            // Delete old image if it exists
            if ($animal['image'] && file_exists(ROOTPATH . 'public/uploads/' . $animal['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $animal['image']);
            }
            
            $imageName = $imageFile->getRandomName();
            $imageFile->move(ROOTPATH . 'public/uploads', $imageName);
        }

        // Prepare data
        $data = [
            'name'        => $this->request->getPost('name'),
            'category'    => $this->request->getPost('category'),
            'age'         => (int)$this->request->getPost('age'),
            'gender'      => $this->request->getPost('gender'),
            'price'       => (float)$this->request->getPost('price'),
            'description' => $this->request->getPost('description') ?: '',
            'image'       => $imageName
        ];

        try {
            if ($this->animalModel->update($id, $data)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Animal updated successfully!',
                    'data' => $data
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update animal.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function deleteAnimal($id = null)
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$id) {
            return $this->response->setJSON(['error' => 'Animal ID required'])->setStatusCode(400);
        }

        $animal = $this->animalModel->find($id);
        if (!$animal) {
            return $this->response->setJSON(['error' => 'Animal not found'])->setStatusCode(404);
        }

        try {
            // Delete image file if it exists
            if ($animal['image'] && file_exists(ROOTPATH . 'public/uploads/' . $animal['image'])) {
                unlink(ROOTPATH . 'public/uploads/' . $animal['image']);
            }

            if ($this->animalModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Animal deleted successfully!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete animal.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    // ========== USER MANAGEMENT METHODS ==========

    public function getUsers()
    {
        // API endpoint to get all users as JSON
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $users = $this->userModel->findAll();
        
        // Remove password from response for security
        foreach ($users as &$user) {
            unset($user['password']);
        }
        
        return $this->response->setJSON($users);
    }

    public function createUser()
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        // Validate input with admin rules (only admin/staff roles allowed)
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'name'     => 'required|min_length[2]|max_length[255]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,staff]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ])->setStatusCode(400);
        }

        // Prepare data
        $data = [
            'name'     => trim($this->request->getPost('name')),
            'email'    => trim($this->request->getPost('email')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role')
        ];

        try {
            if ($this->userModel->save($data)) {
                // Remove password from response
                unset($data['password']);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User created successfully!',
                    'data' => $data
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to create user.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function updateUser($id = null)
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$id) {
            return $this->response->setJSON(['error' => 'User ID required'])->setStatusCode(400);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['error' => 'User not found'])->setStatusCode(404);
        }

        // Validate input
        $validation = \Config\Services::validation();
        
        $rules = [
            'name'  => 'required|min_length[2]|max_length[255]',
            'email' => 'required|valid_email|is_unique[users.email,id,' . $id . ']',
            'role'  => 'required|in_list[customer,admin,staff]'
        ];

        // Only validate password if it's provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
        }

        $validation->setRules($rules);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ])->setStatusCode(400);
        }

        // Prepare data
        $data = [
            'name'  => trim($this->request->getPost('name')),
            'email' => trim($this->request->getPost('email')),
            'role'  => $this->request->getPost('role')
        ];

        // Only update password if provided
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        try {
            if ($this->userModel->update($id, $data)) {
                // Remove password from response
                unset($data['password']);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User updated successfully!',
                    'data' => $data
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to update user.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }

    public function deleteUser($id = null)
    {
        // Check if user is admin
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        if (!$id) {
            return $this->response->setJSON(['error' => 'User ID required'])->setStatusCode(400);
        }

        // Prevent admin from deleting themselves
        if ($id == session()->get('user_id')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You cannot delete your own account.'
            ])->setStatusCode(400);
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->response->setJSON(['error' => 'User not found'])->setStatusCode(404);
        }

        try {
            if ($this->userModel->delete($id)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'User deleted successfully!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Failed to delete user.'
                ])->setStatusCode(500);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
