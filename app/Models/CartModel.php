<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'cart';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 
        'animal_id', 
        'quantity'
    ];
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = null;
    
    // Validation rules
    protected $validationRules = [
        'user_id'   => 'required|integer',
        'animal_id' => 'required|integer',
        'quantity'  => 'required|integer|greater_than[0]'
    ];
    
    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required',
            'integer' => 'User ID must be a valid number'
        ],
        'animal_id' => [
            'required' => 'Animal ID is required',
            'integer' => 'Animal ID must be a valid number'
        ],
        'quantity' => [
            'required' => 'Quantity is required',
            'integer' => 'Quantity must be a valid number',
            'greater_than' => 'Quantity must be greater than 0'
        ]
    ];
    
    protected $skipValidation = true;

    /**
     * Get user's cart with animal details
     */
    public function getUserCart($userId)
    {
        return $this->select('cart.*, animals.name, animals.price, animals.image, animals.status, categories.name as category_name')
                    ->join('animals', 'animals.id = cart.animal_id')
                    ->join('categories', 'categories.id = animals.category_id', 'left')
                    ->where('cart.user_id', $userId)
                    ->where('animals.status', 'available')
                    ->findAll();
    }

    /**
     * Get cart total for user
     */
    public function getCartTotal($userId)
    {
        $cartItems = $this->getUserCart($userId);
        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        return $total;
    }

    /**
     * Get cart item count for user
     */
    public function getCartCount($userId)
    {
        return $this->where('user_id', $userId)->countAllResults();
    }

    /**
     * Add item to cart or update quantity
     */
    public function addToCart($userId, $animalId, $quantity = 1)
    {
        // Check if item already exists in cart
        $existingItem = $this->where('user_id', $userId)
                             ->where('animal_id', $animalId)
                             ->first();
        
        if ($existingItem) {
            // Update quantity
            return $this->update($existingItem['id'], [
                'quantity' => $existingItem['quantity'] + $quantity
            ]);
        } else {
            // Add new item
            return $this->save([
                'user_id' => $userId,
                'animal_id' => $animalId,
                'quantity' => $quantity
            ]);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateQuantity($userId, $animalId, $quantity)
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($userId, $animalId);
        }
        
        return $this->where('user_id', $userId)
                    ->where('animal_id', $animalId)
                    ->set(['quantity' => $quantity])
                    ->update();
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($userId, $animalId)
    {
        return $this->where('user_id', $userId)
                    ->where('animal_id', $animalId)
                    ->delete();
    }

    /**
     * Clear user's cart
     */
    public function clearCart($userId)
    {
        return $this->where('user_id', $userId)->delete();
    }
}
