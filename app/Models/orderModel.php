<?php
namespace App\Models;
use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $allowedFields = ['date', 'customer_name', 'gmail', 'tel_number', 'animal','address', 'total', 'payment_status', 'order_status'];
}


