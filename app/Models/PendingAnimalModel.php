<?php

namespace App\Models;

use CodeIgniter\Model;

class PendingAnimalModel extends Model
{
    protected $table = 'pending_animals';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 
        'category', 
        'category_id',
        'age', 
        'gender',
        'price', 
        'description', 
        'image', 
        'status', 
        'added_by',
        'admin_notes',
        'approved_by',
        'approved_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'name'     => 'required|min_length[2]|max_length[255]',
        'category' => 'required|in_list[cat,dog,bird,hamster,rabbit,fish]',
        'age'      => 'required|integer|greater_than[0]',
        'gender'   => 'required|in_list[male,female]',
        'price'    => 'required|decimal|greater_than[0]',
        'description' => 'max_length[1000]',
        'status'   => 'in_list[pending,approved,rejected]',
        'added_by' => 'required|integer'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Animal name is required',
            'min_length' => 'Animal name must be at least 2 characters long',
            'max_length' => 'Animal name cannot exceed 255 characters'
        ],
        'category' => [
            'required' => 'Category is required',
            'in_list' => 'Category must be one of: cat, dog, bird, hamster, rabbit, fish'
        ],
        'age' => [
            'required' => 'Age is required',
            'integer' => 'Age must be a valid number',
            'greater_than' => 'Age must be greater than 0'
        ],
        'gender' => [
            'required' => 'Gender is required',
            'in_list' => 'Gender must be either male or female'
        ],
        'price' => [
            'required' => 'Price is required',
            'decimal' => 'Price must be a valid decimal number',
            'greater_than' => 'Price must be greater than 0'
        ],
        'description' => [
            'max_length' => 'Description cannot exceed 1000 characters'
        ],
        'status' => [
            'in_list' => 'Status must be pending, approved, or rejected'
        ],
        'added_by' => [
            'required' => 'Added by user ID is required',
            'integer' => 'Added by must be a valid user ID'
        ]
    ];
    
    protected $skipValidation = true;

    /**
     * Get pending animals with user details
     */
    public function getPendingAnimalsWithDetails()
    {
        return $this->select('
            pending_animals.*,
            users.name as added_by_name,
            users.email as added_by_email,
            categories.name as category_name
        ')
        ->join('users', 'users.id = pending_animals.added_by', 'left')
        ->join('categories', 'categories.id = pending_animals.category_id', 'left')
        ->where('pending_animals.status', 'pending')
        ->orderBy('pending_animals.created_at', 'DESC')
        ->findAll();
    }

    /**
     * Get all pending animals (including approved/rejected)
     */
    public function getAllPendingAnimalsWithDetails()
    {
        return $this->select('
            pending_animals.*,
            users.name as added_by_name,
            users.email as added_by_email,
            categories.name as category_name,
            admin_users.name as approved_by_name
        ')
        ->join('users', 'users.id = pending_animals.added_by', 'left')
        ->join('categories', 'categories.id = pending_animals.category_id', 'left')
        ->join('users admin_users', 'admin_users.id = pending_animals.approved_by', 'left')
        ->orderBy('pending_animals.created_at', 'DESC')
        ->findAll();
    }

    /**
     * Get pending animals by user
     */
    public function getPendingAnimalsByUser($userId)
    {
        return $this->select('
            pending_animals.*,
            categories.name as category_name
        ')
        ->join('categories', 'categories.id = pending_animals.category_id', 'left')
        ->where('pending_animals.added_by', $userId)
        ->orderBy('pending_animals.created_at', 'DESC')
        ->findAll();
    }

    /**
     * Approve a pending animal and move it to animals table
     */
    public function approveAnimal($id, $adminId, $adminNotes = null)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get the pending animal
            $pendingAnimal = $this->find($id);
            if (!$pendingAnimal) {
                throw new \Exception('Pending animal not found');
            }

            // Update pending animal status
            $this->update($id, [
                'status' => 'approved',
                'approved_by' => $adminId,
                'approved_at' => date('Y-m-d H:i:s'),
                'admin_notes' => $adminNotes
            ]);

            // Insert into animals table (only valid columns)
            $animalData = [
                'name' => $pendingAnimal['name'],
                'category_id' => $pendingAnimal['category_id'],
                'age' => $pendingAnimal['age'],
                'gender' => $pendingAnimal['gender'],
                'price' => $pendingAnimal['price'],
                'description' => $pendingAnimal['description'],
                'image' => $pendingAnimal['image'],
                'status' => 'available',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $inserted = $db->table('animals')->insert($animalData);
            if (!$inserted) {
                $error = $db->error();
                throw new \Exception('Failed to insert into animals: ' . ($error['message'] ?? 'unknown error'));
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return true;
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Approve animal error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Reject a pending animal
     */
    public function rejectAnimal($id, $adminId, $adminNotes = null)
    {
        return $this->update($id, [
            'status' => 'rejected',
            'approved_by' => $adminId,
            'approved_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $adminNotes
        ]);
    }

    /**
     * Get count of pending animals
     */
    public function getPendingCount()
    {
        return $this->where('status', 'pending')->countAllResults();
    }

    /**
     * Get recent pending animals (for notifications)
     */
    public function getRecentPendingAnimals($limit = 5)
    {
        return $this->select('
            pending_animals.*,
            users.name as added_by_name
        ')
        ->join('users', 'users.id = pending_animals.added_by', 'left')
        ->where('pending_animals.status', 'pending')
        ->orderBy('pending_animals.created_at', 'DESC')
        ->limit($limit)
        ->findAll();
    }
}
