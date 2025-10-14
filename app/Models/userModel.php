<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'role'];
    protected $useTimestamps = false;
    
    // Validation rules
    protected $validationRules = [
        'name'     => 'required|min_length[2]|max_length[255]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[customer,admin,staff]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Name is required',
            'min_length' => 'Name must be at least 2 characters long',
            'max_length' => 'Name cannot exceed 255 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please enter a valid email address',
            'is_unique' => 'This email is already registered'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must be at least 6 characters long'
        ],
        'role' => [
            'required' => 'Role is required',
            'in_list' => 'Role must be customer, admin, or staff'
        ]
    ];
    
    protected $skipValidation = false;
}
