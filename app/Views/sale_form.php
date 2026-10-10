<?= $this->include('templates/header') ?>

<section class="form-card">
    <h1>Record Sale</h1>

    <?php $errors = session()->getFlashdata('errors'); ?>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <?php if (empty($products)): ?>
        <div class="error-message">
            There are no active products with available stock.
            Add stock before recording a sale.
        </div>

        <a
            href="<?= base_url('/products') ?>"
            class="button"
        >
            View Products
        </a>
    <?php else: ?>
        <form
            action="<?= base_url('/sales/create') ?>"
            method="post"
        >
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="product_id">
                    Product
                </label>

                <select
                    id="product_id"
                    name="product_id"
                    required
                >
                    <option value="">
                        Select a product
                    </option>

                    <?php foreach ($products as $product): ?>
                        <option
                            value="<?= esc($product['id']) ?>"
                            <?= old('product_id') == $product['id']
                                ? 'selected'
                                : '' ?>
                        >
                            <?= esc($product['name']) ?>
                            — ₱<?= number_format(
                                (float) $product['price'],
                                2
                            ) ?>
                            — Stock:
                            <?= esc($product['stock_quantity']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="customer_id">
                    Customer
                </label>

                <select
                    id="customer_id"
                    name="customer_id"
                >
                    <option value="">
                        Walk-in Customer
                    </option>

                    <?php foreach ($customers as $customer): ?>
                        <option
                            value="<?= esc($customer['id']) ?>"
                            <?= old('customer_id') == $customer['id']
                                ? 'selected'
                                : '' ?>
                        >
                            <?= esc($customer['full_name']) ?>
                        </option>
                    <?php endforeach ?>
                </select>

                <small>
                    Customer selection is optional.
                </small>
            </div>

            <div class="form-group">
                <label for="quantity">
                    Quantity
                </label>

                <input
                    type="number"
                    id="quantity"
                    name="quantity"
                    value="<?= esc(old('quantity', 1)) ?>"
                    min="1"
                    required
                >
            </div>

            <div class="form-actions">
                <button
                    type="submit"
                    class="button"
                >
                    Record Sale
                </button>

                <a
                    href="<?= base_url('/products') ?>"
                    class="button cancel-button"
                >
                    Cancel
                </a>
            </div>
        </form>
    <?php endif ?>
</section>

<?= $this->include('templates/footer') ?>