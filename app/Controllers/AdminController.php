<?php

namespace App\Controllers;

use App\Models\AnimalModel;

class AdminController extends BaseController
{
    protected $animalModel;

    public function __construct()
    {
        $this->animalModel = new AnimalModel();
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
}
