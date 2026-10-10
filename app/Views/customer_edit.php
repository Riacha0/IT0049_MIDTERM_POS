<?= $this->include('templates/header') ?>

<section class="form-card">
    <h1>Edit Customer</h1>

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

    <form action="<?= base_url('/customers/update/' . $customer['id']) ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="full_name">Full Name</label>

            <input
                type="text"
                id="full_name"
                name="full_name"
                value="<?= esc(old('full_name', $customer['full_name'])) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= esc(old('email', $customer['email'])) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>

            <input
                type="text"
                id="phone"
                name="phone"
                value="<?= esc(old('phone', $customer['phone'])) ?>"
            >
        </div>

        <div class="form-actions">
            <button type="submit" class="button">
                Update Customer
            </button>

            <a href="<?= base_url('/customers') ?>" class="button cancel-button">
                Cancel
            </a>
        </div>
    </form>
</section>

<?= $this->include('templates/footer') ?>