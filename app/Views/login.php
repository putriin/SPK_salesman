<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/pages/login.css') ?>">
</head>

<body>

    <div class="login-page d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-11 col-md-8 col-lg-6 col-xl-5">
                    <div class="card login-card border-0">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <img src="<?= base_url('assets/img/Hamasa Logo.png') ?>" alt="Logo" class="login-logo">
                                <h2 class="fw-bold">Login</h2>
                                <p class="text-muted mb-0">SPK Salesman Hamasa</p>
                            </div>

                            <?php if (session()->getFlashdata('errors')): ?>
                            <div class="alert alert-danger">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <div><?= esc($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                            <form action="<?= base_url('login') ?>" method="post">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" name="username" class="form-control"
                                        placeholder="Masukkan username" value="<?= old('username') ?>" required>
                                </div>

                                <div class="mb-4">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Masukkan password" required>
                                </div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary login-btn">
                                        Login
                                    </button>
                                </div>
                            </form>

                            <div class="or-divider">atau</div>

                            <form id="googleLoginForm" action="<?= base_url('login/google') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="credential" id="googleCredential">
                            </form>

                            <div id="g_id_onload" data-client_id="<?= esc((string) env('google.clientId')) ?>"
                                data-callback="handleGoogleSignIn">
                            </div>

                            <div class="google-wrap mb-2">
                                <div class="g_id_signin" data-type="standard" data-shape="pill" data-theme="outline"
                                    data-text="signin_with" data-size="large" data-logo_alignment="left">
                                </div>
                            </div>

                            <div class="text-center login-footer mt-3">
                                © <?= date('Y') ?> SPK Salesman Hamasa
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script>
    function handleGoogleSignIn(response) {
        document.getElementById('googleCredential').value = response.credential;
        document.getElementById('googleLoginForm').submit();
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>