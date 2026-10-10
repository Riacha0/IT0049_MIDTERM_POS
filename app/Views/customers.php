<?= $this->include('templates/header') ?>

<section class="content-card">
    <div class="page-header">
        <h1>Customer Accounts</h1>

        <a
            href="<?= base_url('/customers/new') ?>"
            class="button add-button"
        >
            Add New Customer
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="success-message">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif ?>

    <?php if (empty($customers)): ?>
        <p>No customer accounts were found.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td>
                            <?= esc($customer['full_name']) ?>
                        </td>

                        <td>
                            <?= esc($customer['email']) ?>
                        </td>

                        <td>
                            <?= esc($customer['phone'] ?? '') ?>
                        </td>

                        <td>
                            <?= esc($customer['created_at']) ?>
                        </td>

                        <td>
                            <a
                                href="<?= base_url(
                                    '/customers/edit/' .
                                    $customer['id']
                                ) ?>"
                                class="button edit-button"
                            >
                                Edit
                            </a>

                            <form
                                action="<?= base_url(
                                    '/customers/delete/' .
                                    $customer['id']
                                ) ?>"
                                method="post"
                                class="inline-form"
                                onsubmit="return confirm('Are you sure you want to delete this customer?');"
                            >
                                <?= csrf_field() ?>

                                <button
                                    type="submit"
                                    class="button delete-button"
                                >
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</section>

<?= $this->include('templates/footer') ?>