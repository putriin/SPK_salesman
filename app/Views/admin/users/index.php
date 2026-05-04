<?= $this->extend('layout/dashboard') ?>

<?= $this->section('page_css') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
function roleLabel($role)
{
    if ($role === 'admin') {
        return 'IT Support';
    }

    if ($role === 'manajer') {
        return 'Manajer';
    }

    if ($role === 'ceo') {
        return 'CEO';
    }

    return ucfirst((string) $role);
}
?>

<div class="container py-4">

    <div class="d-flex align-items-start gap-3 mb-4">
        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">←</a>

        <div>
            <h2 class="fw-bold mb-1">Pengaturan User</h2>
            <p class="text-muted mb-0">Kelola akun user dan reset password.</p>
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
        <div><?= esc((string) $error) ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="<?= base_url('pengaturan-user') ?>"
            class="btn btn-sm <?= ($filter ?? 'all') === 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">
            Semua
        </a>
        <a href="<?= base_url('pengaturan-user/admin') ?>"
            class="btn btn-sm <?= ($filter ?? '') === 'admin' ? 'btn-primary' : 'btn-outline-primary' ?>">
            IT Support
        </a>
        <a href="<?= base_url('pengaturan-user/manajer') ?>"
            class="btn btn-sm <?= ($filter ?? '') === 'manajer' ? 'btn-primary' : 'btn-outline-primary' ?>">
            Manajer
        </a>
        <a href="<?= base_url('pengaturan-user/ceo') ?>"
            class="btn btn-sm <?= ($filter ?? '') === 'ceo' ? 'btn-primary' : 'btn-outline-primary' ?>">
            CEO
        </a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">
                <i class="bi bi-person-plus me-1"></i>
                Tambah User
            </h5>

            <form action="<?= base_url('pengaturan-user/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= old('username') ?>" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="full_name" class="form-control" value="<?= old('full_name') ?>">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">IT Support</option>
                            <option value="manajer">Manajer</option>
                            <option value="ceo">CEO</option>
                        </select>
                    </div>

                    <div class="col-md-1 d-grid">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-people me-1"></i>
                    Daftar User
                </h5>

                <span class="badge text-bg-light border text-dark">
                    Total: <?= count($users ?? []) ?>
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Auth</th>
                            <th style="width: 230px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($users)): ?>
                        <?php $no = 1; ?>

                        <?php foreach ($users as $row): ?>
                        <?php
                            $userId = is_array($row) && isset($row['id']) ? (int) $row['id'] : 0;
                            $userUsername = is_array($row) && isset($row['username']) ? (string) $row['username'] : '-';
                            $userEmail = is_array($row) && isset($row['email']) ? (string) $row['email'] : '-';
                            $userRole = is_array($row) && isset($row['role']) ? (string) $row['role'] : '-';
                            $userAuth = is_array($row) && isset($row['auth_provider']) ? (string) $row['auth_provider'] : 'manual';

                            $roleClass = 'text-bg-secondary';
                            if ($userRole === 'admin') {
                                $roleClass = 'text-bg-danger';
                            } elseif ($userRole === 'manajer') {
                                $roleClass = 'text-bg-primary';
                            } elseif ($userRole === 'ceo') {
                                $roleClass = 'text-bg-success';
                            }
                        ?>

                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-semibold"><?= esc($userUsername) ?></td>
                            <td><?= esc($userEmail) ?></td>
                            <td>
                                <span class="badge rounded-pill <?= $roleClass ?>">
                                    <?= esc(roleLabel($userRole)) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill text-bg-light border">
                                    <?= esc(ucfirst($userAuth)) ?>
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                                    data-bs-target="#resetModal<?= $userId ?>">
                                    Reset Password
                                </button>

                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#hapusModal<?= $userId ?>">
                                    Hapus
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="resetModal<?= $userId ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <form action="<?= base_url('pengaturan-user/reset-password/' . $userId) ?>"
                                        method="post">
                                        <?= csrf_field() ?>

                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">Reset Password</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p class="text-muted">
                                                Reset password untuk <strong><?= esc($userUsername) ?></strong>.
                                            </p>

                                            <input type="password" name="new_password" class="form-control"
                                                placeholder="Password baru" required>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="submit" class="btn btn-warning text-white">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="hapusModal<?= $userId ?>" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        Yakin ingin menghapus user <strong><?= esc($userUsername) ?></strong>?
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <form action="<?= base_url('pengaturan-user/delete/' . $userId) ?>"
                                            method="post">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada user.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>