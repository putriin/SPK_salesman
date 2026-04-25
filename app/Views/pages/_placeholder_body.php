<section class="page">
    <div class="page__header">
        <h1 class="page__title"><?= esc($title ?? 'Halaman') ?></h1>
        <p class="page__desc">Halaman ini masih sementara (placeholder) supaya navigasi frontend dan layout dashboard
            sudah berfungsi. Nanti tinggal isi tabel/form sesuai kebutuhan.</p>
        <a class="btn" href="<?= base_url('dashboard') ?>">← Kembali ke Dashboard</a>
    </div>

    <div class="panel">
        <div class="panel__body">
            <div class="empty">
                <div class="empty__icon">📄</div>
                <div class="empty__text">Konten belum dibuat.</div>
            </div>
        </div>
    </div>
</section>