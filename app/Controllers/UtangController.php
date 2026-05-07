<?php

namespace App\Controllers;

use App\Models\UtangModel;
use App\Models\CustomerModel;
use App\Models\InventoryModel;
use CodeIgniter\Controller;

class UtangController extends BaseController
{
    protected $utangModel;
    protected $customerModel;
    protected $inventoryModel;

    public function __construct()
    {
        $this->utangModel = new UtangModel();
        $this->customerModel = new CustomerModel();
        $this->inventoryModel = new InventoryModel();
    }

    public function index()
    {
        $data = [
            'utangList' => $this->utangModel->getUtangWithDetails(),
            'customers' => $this->customerModel->orderBy('name', 'ASC')->findAll(),
            'products'  => $this->inventoryModel->orderBy('name', 'ASC')->findAll(),
        ];
        return view('dashboard/utang', $data);
    }

    public function store()
    {
        $rules = [
            'customer_id' => 'required',
            'product_id'  => 'required',
            'amount'      => 'required|decimal',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->utangModel->save([
            'customer_id' => $this->request->getPost('customer_id'),
            'product_id'  => $this->request->getPost('product_id'),
            'amount'      => $this->request->getPost('amount'),
            'status'      => 'unpaid',
            'date'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/utang')->with('message', 'Utang record added successfully!');
    }

    public function updateStatus($id, $status)
    {
        if (!in_array($status, ['paid', 'unpaid'])) {
            return redirect()->to('/utang')->with('error', 'Invalid status.');
        }

        $this->utangModel->update($id, ['status' => $status]);
        return redirect()->to('/utang')->with('message', 'Status updated successfully!');
    }

    public function delete($id)
    {
        $this->utangModel->delete($id);
        return redirect()->to('/utang')->with('message', 'Record deleted successfully!');
    }
}
