<?= $this->include('templates/header') ?>

<section class="content-card">
    <div class="page-header">
        <h1>Products</h1>

        <a
            href="<?= base_url('/products/new') ?>"
            class="button add-button"
        >
            Add New Product
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="success-message">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif ?>

    <?php if (empty($products)): ?>
        <p>No products have been added yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if (!empty($product['image'])): ?>
                                <img
                                    src="<?= base_url(
                                        'uploads/products/' .
                                        $product['image']
                                    ) ?>"
                                    alt="<?= esc($product['name']) ?>"
                                    class="product-image"
                                >
                            <?php else: ?>
                                <div class="product-placeholder">
                                    No Image
                                </div>
                            <?php endif ?>
                        </td>

                        <td>
                            <?= esc($product['name']) ?>
                        </td>

                        <td>
                            ₱<?= number_format(
                                (float) $product['price'],
                                2
                            ) ?>
                        </td>

                        <td>
                            <?= esc($product['stock_quantity']) ?>
                        </td>

                        <td>
                            <?php if ($product['is_active']): ?>
                                <span class="status-active">
                                    Active
                                </span>
                            <?php else: ?>
                                <span class="status-archived">
                                    Archived
                                </span>
                            <?php endif ?>
                        </td>

                        <td>
                            <?php if ($product['is_active']): ?>
                                <a
                                    href="<?= base_url(
                                        '/products/edit/' .
                                        $product['id']
                                    ) ?>"
                                    class="button edit-button"
                                >
                                    Edit
                                </a>

                                <form
                                    action="<?= base_url(
                                        '/products/archive/' .
                                        $product['id']
                                    ) ?>"
                                    method="post"
                                    class="inline-form"
                                    onsubmit="return confirm('Are you sure you want to archive this product?');"
                                >
                                    <?= csrf_field() ?>

                                    <button
                                        type="submit"
                                        class="button archive-button"
                                    >
                                        Archive
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="no-action">
                                    No actions
                                </span>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</section>

<?= $this->include('templates/footer') ?>