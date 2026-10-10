<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Customers extends BaseController
{
    public function index()
    {
        $customerModel = new CustomerModel();

        return view('customers', [
            'customers' => $customerModel
                ->orderBy('id', 'DESC')
                ->findAll(),
        ]);
    }

    public function new()
    {
        helper('form');

        return view('customer_form');
    }

    public function create()
    {
        helper('form');

        $rules = [
            'full_name' => [
                'label' => 'Full Name',
                'rules' =>
                    'required|min_length[3]|max_length[100]',
            ],
            'email' => [
                'label' => 'Email Address',
                'rules' =>
                    'required|valid_email|max_length[255]|' .
                    'is_unique[customers.email]',
            ],
            'phone' => [
                'label' => 'Phone Number',
                'rules' =>
                    'permit_empty|max_length[20]|' .
                    'regex_match[/^[0-9-]+$/]',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->validator->getErrors()
                );
        }

        $customerModel = new CustomerModel();

        $customerModel->insert([
            'full_name' =>
                trim($this->request->getPost('full_name')),
            'email' =>
                strtolower(
                    trim($this->request->getPost('email'))
                ),
            'phone' =>
                trim($this->request->getPost('phone')),
            'created_at' =>
                date('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->to('/customers')
            ->with(
                'success',
                'Customer added successfully.'
            );
    }

    public function edit($id)
    {
        helper('form');

        $customerModel = new CustomerModel();
        $customer = $customerModel->find($id);

        if (!$customer) {
            throw PageNotFoundException::forPageNotFound(
                'Customer record not found.'
            );
        }

        return view('customer_edit', [
            'customer' => $customer,
        ]);
    }

    public function update($id)
    {
        helper('form');

        $customerModel = new CustomerModel();
        $customer = $customerModel->find($id);

        if (!$customer) {
            throw PageNotFoundException::forPageNotFound(
                'Customer record not found.'
            );
        }

        $rules = [
            'full_name' => [
                'label' => 'Full Name',
                'rules' =>
                    'required|min_length[3]|max_length[100]',
            ],
            'email' => [
                'label' => 'Email Address',
                'rules' =>
                    "required|valid_email|max_length[255]|" .
                    "is_unique[customers.email,id,{$id}]",
            ],
            'phone' => [
                'label' => 'Phone Number',
                'rules' =>
                    'permit_empty|max_length[20]|' .
                    'regex_match[/^[0-9-]+$/]',
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->validator->getErrors()
                );
        }

        $customerModel->update($id, [
            'full_name' =>
                trim($this->request->getPost('full_name')),
            'email' =>
                strtolower(
                    trim($this->request->getPost('email'))
                ),
            'phone' =>
                trim($this->request->getPost('phone')),
        ]);

        return redirect()
            ->to('/customers')
            ->with(
                'success',
                'Customer updated successfully.'
            );
    }

    public function delete($id)
    {
        $customerModel = new CustomerModel();
        $customer = $customerModel->find($id);

        if (!$customer) {
            throw PageNotFoundException::forPageNotFound(
                'Customer record not found.'
            );
        }

        $customerModel->delete($id);

        return redirect()
            ->to('/customers')
            ->with(
                'success',
                'Customer deleted successfully.'
            );
    }
}