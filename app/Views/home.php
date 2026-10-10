<?= $this->include('templates/header') ?>

<section class="content-card home-card">
    <h1>Welcome to the POS System</h1>

    <p>
        This Point-of-Sale system manages products, customers,
        staff accounts, inventory, and sales transactions.
    </p>

    <?php if (session()->get('isLoggedIn')): ?>
        <p>
            Use the navigation menu above to manage the POS system.
        </p>
    <?php else: ?>
        <a
            href="<?= base_url('/login') ?>"
            class="button"
        >
            Login to Continue
        </a>
    <?php endif ?>
</section>

<?= $this->include('templates/footer') ?>