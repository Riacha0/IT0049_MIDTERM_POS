<?= $this->include('templates/header') ?>

<section class="form-card">
    <h1>Add New Product</h1>

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

    <form
        action="<?= base_url('/products/create') ?>"
        method="post"
        enctype="multipart/form-data"
    >
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="name">Product Name</label>
            <input
                type="text"
                id="name"
                name="name"
                value="<?= esc(old('name')) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input
                type="number"
                id="price"
                name="price"
                value="<?= esc(old('price')) ?>"
                min="0.01"
                step="0.01"
                required
            >
        </div>

        <div class="form-group">
            <label for="stock_quantity">Stock Quantity</label>
            <input
                type="number"
                id="stock_quantity"
                name="stock_quantity"
                value="<?= esc(old('stock_quantity')) ?>"
                min="0"
                required
            >
        </div>

        <div class="form-group">
            <label for="image">Product Image</label>
            <input
                type="file"
                id="image"
                name="image"
                accept=".jpg,.jpeg,.png,.webp"
                required
            >
            <small>JPG, PNG, or WebP only. Maximum size: 2 MB.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="button">
                Save Product
            </button>

            <a href="<?= base_url('/products') ?>" class="button cancel-button">
                Cancel
            </a>
        </div>
    </form>
</section>

<?= $this->include('templates/footer') ?>