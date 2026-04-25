<?= $this->extend('auth_layout') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('success')): ?>
<div class="auth-success">
    <?= esc(session()->getFlashdata('success')) ?>
</div>
<?php endif; ?>

<div class="auth-page">
    <div class="auth-toggle">
        <a class="toggle-pill" href="<?= base_url('login') ?>">login</a>
        <a class="toggle-pill active" href="<?= base_url('signup') ?>">signup</a>
    </div>

    <div class="auth-card">
        <div class="logo-wrap">
            <img class="logo" src="<?= base_url('assets/img/logo.png') ?>" alt="Logo">
        </div>

        <h1 class="title">Buat Akun</h1>

        <?php $errors = session('errors') ?? []; ?>
        <?php if (!empty($errors)): ?>
        <div class="auth-alert">
            <?php foreach ($errors as $e): ?>
            <div><?= esc($e) ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
        <div class="auth-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('signup') ?>" class="auth-form">
            <?= csrf_field() ?>

            <div class="field">
                <input type="email" name="email" placeholder="Email" value="<?= old('email') ?>" required>
            </div>

            <div class="field">
                <input type="text" name="username" placeholder="Username" value="<?= old('username') ?>" required>
            </div>

            <div class="field">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <div class="actions-center">
                <button class="btn-primary" type="submit">Buat Akun</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>