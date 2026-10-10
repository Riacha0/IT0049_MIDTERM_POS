<?= $this->include('templates/header') ?>

<section class="content-card">
    <div class="page-header">
        <h1>Sales History</h1>

        <a
            href="<?= base_url('/sales/new') ?>"
            class="button add-button"
        >
            Record New Sale
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="success-message">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif ?>

    <?php if (empty($sales)): ?>
        <p>No sales have been recorded yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Staff</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td>
                            <?= esc($sale['product_name']) ?>
                        </td>

                        <td>
                            <?= !empty($sale['customer_name'])
                                ? esc($sale['customer_name'])
                                : 'Walk-in Customer' ?>
                        </td>

                        <td>
                            <?= esc($sale['staff_name']) ?>
                        </td>

                        <td>
                            <?= esc($sale['quantity']) ?>
                        </td>

                        <td>
                            ₱<?= number_format(
                                (float) $sale['total_price'],
                                2
                            ) ?>
                        </td>

                        <td>
                            <?= esc(
                                date(
                                    'M d, Y h:i A',
                                    strtotime($sale['created_at'])
                                )
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php endif ?>
</section>

<?= $this->include('templates/footer') ?>