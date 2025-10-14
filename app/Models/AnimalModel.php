<?php

namespace App\Models;

use CodeIgniter\Model;

class AnimalModel extends Model
{
    protected $table = 'animals';
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
        'added_by'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = null;
    
    // Validation rules
    protected $validationRules = [
        'name'     => 'required|min_length[2]|max_length[255]',
        'category' => 'required|in_list[cat,dog,bird,hamster,rabbit,fish]',
        'age'      => 'required|integer|greater_than[0]',
        'gender'   => 'required|in_list[male,female]',
        'price'    => 'required|decimal|greater_than[0]',
        'description' => 'max_length[1000]',
        'status'   => 'in_list[available,sold,reserved]',
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
            'in_list' => 'Status must be available, sold, or reserved'
        ],
        'added_by' => [
            'required' => 'Added by user ID is required',
            'integer' => 'Added by must be a valid user ID'
        ]
    ];
    
    protected $skipValidation = true;

    /**
     * Get animals by category
     */
    public function getByCategory($category)
    {
        return $this->where('category', $category)
                    ->where('status', 'available')
                    ->findAll();
    }

    /**
     * Get animals by category ID
     */
    public function getByCategoryId($categoryId)
    {
        return $this->where('category_id', $categoryId)
                    ->where('status', 'available')
                    ->findAll();
    }

    /**
     * Get animals with category details
     */
    public function getAnimalsWithCategory()
    {
        return $this->select('animals.*, categories.name as category_name')
                    ->join('categories', 'categories.id = animals.category_id', 'left')
                    ->where('animals.status', 'available')
                    ->findAll();
    }

    /**
     * Get featured animals (latest available)
     */
    public function getFeaturedAnimals($limit = 8)
    {
        return $this->select('animals.*, categories.name as category_name')
                    ->join('categories', 'categories.id = animals.category_id', 'left')
                    ->where('animals.status', 'available')
                    ->orderBy('animals.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get available animals
     */
    public function getAvailable()
    {
        return $this->where('status', 'available')->findAll();
    }

    /**
     * Get animals with user info
     */
    public function getAnimalsWithUser()
    {
        return $this->select('animals.*, users.name as added_by_name')
                    ->join('users', 'users.id = animals.added_by', 'left')
                    ->findAll();
    }

    /**
     * Search animals
     */
    public function searchAnimals($query, $category = null)
    {
        $builder = $this->builder();
        
        if ($query) {
            $builder->groupStart()
                    ->like('name', $query)
                    ->orLike('description', $query)
                    ->groupEnd();
        }
        
        if ($category) {
            $builder->where('category', $category);
        }
        
        return $builder->where('status', 'available')->get()->getResultArray();
    }
}
