<?php

namespace App\Controllers;

use App\Models\StudentModel;

class Student extends BaseController
{
    public function index()
    {
        $studentModel = new StudentModel();
        $data['students'] = $studentModel->findAll();

        return view('students/index', $data);
    }

    public function create()
    {
        return view('students/create');
    }

    public function store()
    {
        $studentModel = new StudentModel();

        $file = $this->request->getFile('profile_picture');
        $newName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads', $newName);
        }

        $studentModel->save([
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'profile_picture' => $newName,
        ]);

        return redirect()->to('/student');
    }

    public function delete($id)
    {
        $studentModel = new StudentModel();
        $student = $studentModel->find($id);

        if ($student) {
            if ($student['profile_picture'] && file_exists(ROOTPATH . 'public/uploads/' . $student['profile_picture'])) {
                unlink(ROOTPATH . 'public/uploads/' . $student['profile_picture']);
            }
            $studentModel->delete($id);
        }

        return redirect()->to('/student');
    }
}
