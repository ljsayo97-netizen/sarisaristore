<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Controller;

class CustomerController extends BaseController
{
    protected $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function index()
    {
        $data['customers'] = $this->customerModel->orderBy('customer_id', 'DESC')->findAll();
        return view('dashboard/customers', $data);
    }

    public function store()
    {
        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => 'permit_empty|valid_email',
            'phone' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customerModel->save([
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ]);

        return redirect()->to('/customers')->with('message', 'Customer added successfully!');
    }

    public function update($id)
    {
        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => 'permit_empty|valid_email',
            'phone' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->customerModel->update($id, [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ]);

        return redirect()->to('/customers')->with('message', 'Customer updated successfully!');
    }

    public function delete($id)
    {
        $this->customerModel->delete($id);
        return redirect()->to('/customers')->with('message', 'Customer deleted successfully!');
    }
}
