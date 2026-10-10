<?= $this->include('templates/header') ?>

<section class="content-card">
    <div class="page-header">
        <h1>User Accounts</h1>

        <a
            href="<?= base_url('/users/new') ?>"
            class="button add-button"
        >
            Add New User
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="success-message">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="error-message">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif ?>

    <?php if (empty($users)): ?>
        <p>No user accounts were found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <?php if (!empty($user['avatar'])): ?>
                                <img
                                    src="<?= base_url(
                                        'uploads/avatars/' .
                                        $user['avatar']
                                    ) ?>"
                                    alt="<?= esc($user['full_name']) ?>"
                                    class="table-avatar"
                                >
                            <?php else: ?>
                                <div class="avatar-placeholder">
                                    <?= esc(
                                        strtoupper(
                                            substr(
                                                $user['full_name'],
                                                0,
                                                1
                                            )
                                        )
                                    ) ?>
                                </div>
                            <?php endif ?>
                        </td>

                        <td>
                            <?= esc($user['username']) ?>
                        </td>

                        <td>
                            <?= esc($user['full_name']) ?>
                        </td>

                        <td>
                            <?= esc($user['created_at']) ?>
                        </td>

                        <td>
                            <a
                                href="<?= base_url(
                                    '/users/edit/' .
                                    $user['id']
                                ) ?>"
                                class="button edit-button"
                            >
                                Edit
                            </a>

                            <?php if (
                                (int) session()->get('user_id') !==
                                (int) $user['id']
                            ): ?>
                                <form
                                    action="<?= base_url(
                                        '/users/delete/' .
                                        $user['id']
                                    ) ?>"
                                    method="post"
                                    class="inline-form"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');"
                                >
                                    <?= csrf_field() ?>

                                    <button
                                        type="submit"
                                        class="button delete-button"
                                    >
                                        Delete
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="current-account">
                                    Current account
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