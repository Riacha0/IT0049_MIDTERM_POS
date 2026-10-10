<?= $this->include('templates/header') ?>

<section class="form-card">
    <h1>Add New User</h1>

    <?php $errors = session()->getFlashdata('errors'); ?>

    <?php if (!empty($errors)): ?>
        <div class="error-message">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/users/create') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="username">Username</label>

            <input
                type="text"
                id="username"
                name="username"
                value="<?= esc(old('username')) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="full_name">Full Name</label>

            <input
                type="text"
                id="full_name"
                name="full_name"
                value="<?= esc(old('full_name')) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                required
            >
        </div>

        <div class="form-group">
            <label for="password_confirm">Confirm Password</label>

            <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                minlength="8"
                required
            >
        </div>

        <div class="form-actions">
            <button type="submit" class="button">
                Save User
            </button>

            <a
                href="<?= base_url('/users') ?>"
                class="button cancel-button"
            >
                Cancel
            </a>
        </div>
    </form>
</section>

<?= $this->include('templates/footer') ?>