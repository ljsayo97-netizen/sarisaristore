<?php

namespace App\Models;

use CodeIgniter\Model;

class UtangModel extends Model
{
    protected $table            = 'utang';
    protected $primaryKey       = 'utang_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['customer_id', 'product_id', 'amount', 'status', 'date'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getUtangWithDetails()
    {
        return $this->select('utang.*, customers.name as customer_name, products.name as product_name')
                    ->join('customers', 'customers.customer_id = utang.customer_id')
                    ->join('products', 'products.product_id = utang.product_id')
                    ->orderBy('utang.date', 'DESC')
                    ->findAll();
    }
}
