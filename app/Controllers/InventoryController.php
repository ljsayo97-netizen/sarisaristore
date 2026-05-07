<?php

namespace App\Controllers;

use App\Models\InventoryModel;
use CodeIgniter\Controller;

class InventoryController extends BaseController
{
    protected $inventoryModel;

    public function __construct()
    {
        $this->inventoryModel = new InventoryModel();
    }

    public function index()
    {
        $data['products'] = $this->inventoryModel->orderBy('product_id', 'DESC')->findAll();
        return view('dashboard/inventory', $data);
    }

    public function store()
    {
        $rules = [
            'name'  => 'required',
            'price' => 'required|greater_than[0]',
            'stock' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->inventoryModel->save([
            'name'  => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
        ]);

        return redirect()->to('/inventory')->with('message', 'Product added successfully!');
    }

    public function update($id)
    {
        $rules = [
            'name'  => 'required',
            'price' => 'required|greater_than[0]',
            'stock' => 'required|is_natural',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->inventoryModel->update($id, [
            'name'  => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
        ]);

        return redirect()->to('/inventory')->with('message', 'Product updated successfully!');
    }

    public function delete($id)
    {
        $this->inventoryModel->delete($id);
        return redirect()->to('/inventory')->with('message', 'Product deleted successfully!');
    }
}
