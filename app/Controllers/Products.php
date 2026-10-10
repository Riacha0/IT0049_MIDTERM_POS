<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Products extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();

        return view('products', [
            'products' => $productModel
                ->orderBy('id', 'DESC')
                ->findAll(),
        ]);
    }

    public function new()
    {
        helper('form');

        return view('product_form');
    }

    public function create()
    {
        helper('form');

        $rules = [
            'name' => [
                'label' => 'Product Name',
                'rules' => 'required|min_length[2]|max_length[100]',
            ],
            'price' => [
                'label' => 'Price',
                'rules' => 'required|decimal|greater_than[0]',
            ],
            'stock_quantity' => [
                'label' => 'Stock Quantity',
                'rules' => 'required|integer|greater_than_equal_to[0]',
            ],
            'image' => [
                'label' => 'Product Image',
                'rules' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
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

        $image = $this->request->getFile('image');

        if (!$image->isValid() || $image->hasMoved()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', [
                    'image' =>
                        'The product image could not be uploaded.',
                ]);
        }

        $uploadPath = FCPATH . 'uploads/products';

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newImageName = $image->getRandomName();

        service('image')
            ->withFile($image->getTempName())
            ->fit(600, 600, 'center')
            ->save(
                $uploadPath .
                DIRECTORY_SEPARATOR .
                $newImageName
            );

        $productModel = new ProductModel();

        $productModel->insert([
            'name' =>
                trim($this->request->getPost('name')),
            'price' =>
                $this->request->getPost('price'),
            'stock_quantity' =>
                $this->request->getPost('stock_quantity'),
            'image' => $newImageName,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()
            ->to('/products')
            ->with(
                'success',
                'Product added successfully.'
            );
    }

    public function edit($id)
    {
        helper('form');

        $productModel = new ProductModel();
        $product = $productModel->find($id);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound(
                'Product record not found.'
            );
        }

        return view('product_edit', [
            'product' => $product,
        ]);
    }

    public function update($id)
    {
        helper('form');

        $productModel = new ProductModel();
        $product = $productModel->find($id);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound(
                'Product record not found.'
            );
        }

        $rules = [
            'name' => [
                'label' => 'Product Name',
                'rules' => 'required|min_length[2]|max_length[100]',
            ],
            'price' => [
                'label' => 'Price',
                'rules' => 'required|decimal|greater_than[0]',
            ],
            'stock_quantity' => [
                'label' => 'Stock Quantity',
                'rules' => 'required|integer|greater_than_equal_to[0]',
            ],
        ];

        $image = $this->request->getFile('image');

        if (
            $image &&
            $image->getError() !== UPLOAD_ERR_NO_FILE
        ) {
            $rules['image'] = [
                'label' => 'Product Image',
                'rules' => 'uploaded[image]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]|max_size[image,2048]',
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->validator->getErrors()
                );
        }

        $updateData = [
            'name' =>
                trim($this->request->getPost('name')),
            'price' =>
                $this->request->getPost('price'),
            'stock_quantity' =>
                $this->request->getPost('stock_quantity'),
        ];

        if (
            $image &&
            $image->getError() !== UPLOAD_ERR_NO_FILE &&
            $image->isValid() &&
            !$image->hasMoved()
        ) {
            $uploadPath = FCPATH . 'uploads/products';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $newImageName = $image->getRandomName();

            service('image')
                ->withFile($image->getTempName())
                ->fit(600, 600, 'center')
                ->save(
                    $uploadPath .
                    DIRECTORY_SEPARATOR .
                    $newImageName
                );

            if (!empty($product['image'])) {
                $oldImagePath =
                    $uploadPath .
                    DIRECTORY_SEPARATOR .
                    $product['image'];

                if (is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $updateData['image'] = $newImageName;
        }

        $productModel->update($id, $updateData);

        return redirect()
            ->to('/products')
            ->with(
                'success',
                'Product updated successfully.'
            );
    }

    public function archive($id)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($id);

        if (!$product) {
            throw PageNotFoundException::forPageNotFound(
                'Product record not found.'
            );
        }

        $productModel->update($id, [
            'is_active' => 0,
        ]);

        return redirect()
            ->to('/products')
            ->with(
                'success',
                'Product archived successfully.'
            );
    }
}