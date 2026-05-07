<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use App\Models\SaleModel;
use App\Models\SaleItemModel;
use CodeIgniter\Controller;

class SalesController extends BaseController
{
    protected $inventoryModel;
    protected $saleModel;
    protected $saleItemModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
        $this->saleModel = new SaleModel();
        $this->saleItemModel = new SaleItemModel();
    }

    public function index()
    {
        $data['products'] = $this->inventoryModel->where('stock >', 0)->orderBy('name', 'ASC')->findAll();
        return view('dashboard/pos', $data);
    }

    public function store()
    {
        $cart = $this->request->getPost('cart');
        $cash = $this->request->getPost('cash');
        $total = $this->request->getPost('total_amount');

        if (empty($cart) || !is_array($cart)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cart is empty']);
        }

        if ($cash < $total) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Insufficient cash']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Create Sale Record
        $saleData = [
            'date'          => date('Y-m-d H:i:s'),
            'total_amount'  => $total,
            'cash'          => $cash,
            'change_amount' => $cash - $total,
            'user_id'       => session()->get('id')
        ];
        $this->saleModel->insert($saleData);
        $saleId = $this->saleModel->getInsertID();

        // 2. Create Sale Items and Update Stock
        foreach ($cart as $item) {
            $product = $this->inventoryModel->find($item['id']);
            
            if (!$product || $product['stock'] < $item['qty']) {
                $db->transRollback();
                return $this->response->setJSON(['status' => 'error', 'message' => 'Product ' . ($product['name'] ?? 'Unknown') . ' is out of stock']);
            }

            // Record Item
            $this->saleItemModel->insert([
                'sale_id'    => $saleId,
                'product_id' => $item['id'],
                'quantity'   => $item['qty']
            ]);

            // Deduct Stock
            $this->inventoryModel->update($item['id'], [
                'stock' => $product['stock'] - $item['qty']
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Transaction failed']);
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Sale completed successfully!',
            'change' => number_format($cash - $total, 2)
        ]);
    }
}
