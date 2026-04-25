<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CariIn - Marketplace properti terpercaya di Indonesia. Jual, beli, sewa rumah, tanah, apartemen, ruko, dan properti lainnya.">
    <title><?= esc($title ?? 'CariIn - Real Estate Marketplace') ?></title>

    <link rel="icon" type="image/svg+xml" href="<?= asset_url('img/logo.svg') ?>">

    <!-- Google Fonts: Plus Jakarta Sans + Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css">
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- App CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/app.css') ?>">
</head>
<body>
    <?= $this->include('partials/navbar') ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="container-fluid px-0">
            <div class="alert alert-success alert-dismissible fade show rounded-0 mb-0 text-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="container-fluid px-0">
            <div class="alert alert-danger alert-dismissible fade show rounded-0 mb-0 text-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('info')): ?>
        <div class="container-fluid px-0">
            <div class="alert alert-info alert-dismissible fade show rounded-0 mb-0 text-center" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i><?= esc(session()->getFlashdata('info')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <main>
        <?= $page_content ?? '' ?>
    </main>

    <?= $this->include('partials/footer') ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery 3.7 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Leaflet -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>const BASE_URL = '<?= rtrim(base_url(), '/') ?>';</script>
    <!-- App JS -->
    <script src="<?= asset_url('js/app.js') ?>"></script>
</body>
</html>
