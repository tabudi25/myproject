<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryPriceModel extends Model
{
    protected $table = 'category_prices';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'category_id',
        'price_type',
        'price',
        'description'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'category_id' => 'required|integer',
        'price_type' => 'required|max_length[100]',
        'price' => 'required|decimal'
    ];
    
    protected $validationMessages = [
        'category_id' => [
            'required' => 'Category ID is required',
            'integer' => 'Category ID must be an integer'
        ],
        'price_type' => [
            'required' => 'Price type is required',
            'max_length' => 'Price type cannot exceed 100 characters'
        ],
        'price' => [
            'required' => 'Price is required',
            'decimal' => 'Price must be a valid decimal number'
        ]
    ];
    
    protected $skipValidation = false;

    /**
     * Get prices for a specific category
     */
    public function getPricesByCategory($categoryId)
    {
        return $this->where('category_id', $categoryId)
                    ->orderBy('price_type', 'ASC')
                    ->findAll();
    }

    /**
     * Get price by category and type
     */
    public function getPriceByCategoryAndType($categoryId, $priceType)
    {
        return $this->where('category_id', $categoryId)
                    ->where('price_type', $priceType)
                    ->first();
    }

    /**
     * Save or update price for a category
     */
    public function savePrice($categoryId, $priceType, $price, $description = null)
    {
        $existing = $this->getPriceByCategoryAndType($categoryId, $priceType);
        
        $data = [
            'category_id' => $categoryId,
            'price_type' => $priceType,
            'price' => $price,
            'description' => $description
        ];
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }
}

