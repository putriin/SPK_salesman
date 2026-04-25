<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/pages/admin-users.css') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="admin-users-page">
    <div class="d-flex align-items-start gap-3 mb-4">
        <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-outline-secondary btn-sm admin-back-btn"
            title="Kembali ke Dashboard">
            ←
        </a>

        <div>
            <h2 class="fw-bold mb-1"><?= esc($title) ?></h2>
            <p class="text-muted mb-0">Manajemen data user berdasarkan role dan email Google.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
        <div><?= esc($error) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="<?= base_url('admin/users') ?>"
            class="btn <?= $filter === 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">Semua User</a>
        <a href="<?= base_url('admin/users/admin') ?>"
            class="btn <?= $filter === 'admin' ? 'btn-primary' : 'btn-outline-primary' ?>">Admin</a>
        <a href="<?= base_url('admin/users/manajer') ?>"
            class="btn <?= $filter === 'manajer' ? 'btn-primary' : 'btn-outline-primary' ?>">Manajer</a>
        <a href="<?= base_url('admin/users/ceo') ?>"
            class="btn <?= $filter === 'ceo' ? 'btn-primary' : 'btn-outline-primary' ?>">CEO</a>
    </div>

    <div class="card border-0 shadow-sm mb-4 admin-users-card">
        <div class="card-body">
            <h4 class="fw-bold mb-3">Tambah User</h4>

            <form action="<?= base_url('admin/users/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                    </div>

                    <div class="col-lg-3">
                        <label class="form-label">Email Google</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" value="<?= old('full_name') ?>">
                    </div>

                    <div class="col-lg-2">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="passwordTambah" class="form-control" required>
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePassword('passwordTambah', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-lg-1">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="manajer" <?= old('role') === 'manajer' ? 'selected' : '' ?>>Manajer</option>
                            <option value="ceo" <?= old('role') === 'ceo' ? 'selected' : '' ?>>CEO</option>
                        </select>
                    </div>

                    <div class="col-lg-1 d-grid">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm admin-users-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h4 class="fw-bold mb-0">Daftar User</h4>
                <span class="text-muted">Total: <?= count($users) ?></span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 70px;">No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th style="width: 140px;">Role</th>
                            <th style="width: 120px;">Auth</th>
                            <th style="width: 220px;">Dibuat</th>
                            <th style="width: 360px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-medium"><?= esc($user['username']) ?></td>
                            <td><?= esc($user['email'] ?? '-') ?></td>
                            <td>
                                <?php
                                $roleClass = 'text-bg-light border';
                                if ($user['role'] === 'admin') $roleClass = 'text-bg-danger';
                                if ($user['role'] === 'manajer') $roleClass = 'text-bg-primary';
                                if ($user['role'] === 'ceo') $roleClass = 'text-bg-success';
                                ?>
                                <span class="badge rounded-pill <?= $roleClass ?>">
                                    <?= esc(ucfirst($user['role'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-light border">
                                    <?= esc(ucfirst($user['auth_provider'] ?? 'manual')) ?>
                                </span>
                            </td>
                            <td><?= esc($user['created_at'] ?? '-') ?></td>
                            <td>
                                <div class="d-flex flex-column gap-2">

                                    <form action="<?= base_url('admin/users/update-role/' . $user['id']) ?>"
                                        method="post" class="row g-2">
                                        <?= csrf_field() ?>
                                        <div class="col-7">
                                            <select name="role" class="form-select form-select-sm">
                                                <option value="admin"
                                                    <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                <option value="manajer"
                                                    <?= $user['role'] === 'manajer' ? 'selected' : '' ?>>Manajer
                                                </option>
                                                <option value="ceo" <?= $user['role'] === 'ceo' ? 'selected' : '' ?>>CEO
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-5 d-grid">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">Edit
                                                Role</button>
                                        </div>
                                    </form>

                                    <form action="<?= base_url('admin/users/reset-password/' . $user['id']) ?>"
                                        method="post" class="row g-2">
                                        <?= csrf_field() ?>

                                        <div class="col-7">
                                            <div class="input-group input-group-sm">
                                                <input type="password" name="new_password"
                                                    id="resetPassword<?= esc($user['id']) ?>" class="form-control"
                                                    placeholder="Password baru" required>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    onclick="togglePassword('resetPassword<?= esc($user['id']) ?>', this)">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-5 d-grid">
                                            <button type="submit" class="btn btn-warning btn-sm text-white">
                                                Reset Password
                                            </button>
                                        </div>
                                    </form>

                                    <form action="<?= base_url('admin/users/delete/' . $user['id']) ?>" method="post"
                                        onsubmit="return confirm('Hapus user ini?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm w-100">Hapus User</button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada user.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>

<?= $this->endSection() ?>