<?= view('templates/header') ?>

<div class="card">
    <h1>Add New Customer</h1>

    <?php $errors = session('errors'); ?>

    <?php if (! empty($errors)): ?>
        <div class="validation-errors">
            <strong>Please correct the following:</strong>

            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <form action="<?= base_url('/customers/create') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input
                type="text"
                id="full_name"
                name="full_name"
                value="<?= old('full_name') ?>"
            >
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= old('email') ?>"
            >
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input
                type="text"
                id="phone"
                name="phone"
                value="<?= old('phone') ?>"
            >
        </div>

        <button type="submit" class="button">Save Customer</button>
        <a href="<?= base_url('/customers') ?>" class="button secondary">
            Cancel
        </a>
    </form>
</div>

<?= view('templates/footer') ?>