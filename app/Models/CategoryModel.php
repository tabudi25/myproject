<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 
        'description',
        'image', 
        'status'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = null;
    
    // Validation rules
    protected $validationRules = [
        'name'        => 'required|min_length[2]|max_length[255]|is_unique[categories.name,id,{id}]',
        'status'      => 'in_list[active,inactive]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'Category name is required',
            'min_length' => 'Category name must be at least 2 characters long',
            'max_length' => 'Category name cannot exceed 255 characters',
            'is_unique' => 'This category name already exists'
        ],
        'status' => [
            'in_list' => 'Status must be active or inactive'
        ]
    ];
    
    protected $skipValidation = true;

    /**
     * Get active categories
     */
    public function getActive()
    {
        return $this->where('status', 'active')->findAll();
    }

    /**
     * Get categories with animal count
     */
    public function getCategoriesWithCount()
    {
        return $this->select('categories.*, COUNT(animals.id) as animal_count')
                    ->join('animals', 'animals.category_id = categories.id AND animals.status = "available"', 'left')
                    ->groupBy('categories.id')
                    ->findAll();
    }

    /**
     * Get category with animals
     */
    public function getCategoryWithAnimals($categoryId)
    {
        $category = $this->find($categoryId);
        if ($category) {
            $animalModel = new AnimalModel();
            $category['animals'] = $animalModel->where('category_id', $categoryId)
                                              ->where('status', 'available')
                                              ->findAll();
        }
        return $category;
    }
}
