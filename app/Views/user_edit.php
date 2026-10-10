<?= $this->include('templates/header') ?>

<section class="form-card">
    <h1>Edit User</h1>

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
        action="<?= base_url('/users/update/' . $user['id']) ?>"
        method="post"
        enctype="multipart/form-data"
    >
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                value="<?= esc(old('username', $user['username'])) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input
                type="text"
                id="full_name"
                name="full_name"
                value="<?= esc(old('full_name', $user['full_name'])) ?>"
                required
            >
        </div>

        <?php if (!empty($user['avatar'])): ?>
            <div class="form-group">
                <p>Current Avatar</p>
                <img
                    src="<?= base_url('uploads/avatars/' . $user['avatar']) ?>"
                    alt="User avatar"
                    class="avatar-preview"
                >
            </div>
        <?php endif ?>

        <div class="form-group">
            <label for="avatar">Upload Avatar</label>
            <input
                type="file"
                id="avatar"
                name="avatar"
                accept=".jpg,.jpeg,.png"
            >
            <small>JPG or PNG only. Maximum size: 2 MB.</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="button">
                Update User
            </button>

            <a href="<?= base_url('/users') ?>" class="button cancel-button">
                Cancel
            </a>
        </div>
    </form>
</section>

<?= $this->include('templates/footer') ?>