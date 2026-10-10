<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CustomerModel;
use Config\Database;

class Sales extends BaseController
{
    public function index()
    {
        $db = Database::connect();

        $sales = $db
            ->table('sales')
            ->select(
                'sales.id,
                 sales.quantity,
                 sales.total_price,
                 sales.created_at,
                 products.name AS product_name,
                 customers.full_name AS customer_name,
                 users.full_name AS staff_name'
            )
            ->join(
                'products',
                'products.id = sales.product_id'
            )
            ->join(
                'customers',
                'customers.id = sales.customer_id',
                'left'
            )
            ->join(
                'users',
                'users.id = sales.sold_by'
            )
            ->orderBy('sales.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('sales', [
            'sales' => $sales,
        ]);
    }

    public function new()
    {
        helper('form');

        $productModel = new ProductModel();
        $customerModel = new CustomerModel();

        return view('sale_form', [
            'products' => $productModel
                ->where('is_active', 1)
                ->where('stock_quantity >', 0)
                ->orderBy('name', 'ASC')
                ->findAll(),

            'customers' => $customerModel
                ->orderBy('full_name', 'ASC')
                ->findAll(),
        ]);
    }

    public function create()
    {
        helper('form');

        $rules = [
            'product_id' => [
                'label' => 'Product',
                'rules' => 'required|integer',
            ],
            'customer_id' => [
                'label' => 'Customer',
                'rules' => 'permit_empty|integer',
            ],
            'quantity' => [
                'label' => 'Quantity',
                'rules' =>
                    'required|integer|greater_than[0]',
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

        $productId =
            (int) $this->request->getPost('product_id');

        $customerId =
            $this->request->getPost('customer_id');

        $quantity =
            (int) $this->request->getPost('quantity');

        $staffId =
            (int) session()->get('user_id');

        if ($staffId <= 0) {
            return redirect()
                ->to('/login')
                ->with(
                    'error',
                    'Please log in before recording a sale.'
                );
        }

        $db = Database::connect();
        $db->transBegin();

        try {
            $product = $db->query(
                'SELECT * FROM products
                 WHERE id = ?
                 AND is_active = 1
                 FOR UPDATE',
                [$productId]
            )->getRowArray();

            if (!$product) {
                $db->transRollback();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('errors', [
                        'product_id' =>
                            'The selected product is unavailable.',
                    ]);
            }

            if (
                $quantity >
                (int) $product['stock_quantity']
            ) {
                $db->transRollback();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('errors', [
                        'quantity' =>
                            'The requested quantity exceeds ' .
                            'the available stock of ' .
                            $product['stock_quantity'] . '.',
                    ]);
            }

            if (!empty($customerId)) {
                $customerExists = $db
                    ->table('customers')
                    ->where(
                        'id',
                        (int) $customerId
                    )
                    ->countAllResults();

                if ($customerExists === 0) {
                    $db->transRollback();

                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('errors', [
                            'customer_id' =>
                                'The selected customer does not exist.',
                        ]);
                }

                $customerId = (int) $customerId;
            } else {
                $customerId = null;
            }

            $totalPrice =
                (float) $product['price'] * $quantity;

            $newStock =
                (int) $product['stock_quantity'] -
                $quantity;

            $db->table('sales')->insert([
                'product_id' => $productId,
                'customer_id' => $customerId,
                'sold_by' => $staffId,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $db->table('products')
                ->where('id', $productId)
                ->update([
                    'stock_quantity' => $newStock,
                ]);

            if ($db->transStatus() === false) {
                $db->transRollback();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with('errors', [
                        'sale' =>
                            'The sale could not be completed.',
                    ]);
            }

            $db->transCommit();

            return redirect()
                ->to('/sales')
                ->with(
                    'success',
                    'Sale recorded successfully.'
                );
        } catch (\Throwable $exception) {
            $db->transRollback();

            log_message(
                'error',
                'Sale error: ' .
                $exception->getMessage()
            );

            return redirect()
                ->back()
                ->withInput()
                ->with('errors', [
                    'sale' =>
                        'An error occurred while recording the sale.',
                ]);
        }
    }
}