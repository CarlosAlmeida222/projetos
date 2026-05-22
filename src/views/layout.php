<?php
if (!isset($pageTitle)) {
    $pageTitle = 'Sistema LAMP';
}
if (!isset($breadcrumbs)) {
    $breadcrumbs = [];
}
if (!isset($activeMenu)) {
    $activeMenu = '';
}
if (!isset($showSidebar)) {
    $showSidebar = true;
}
if (!isset($pageBody)) {
    $pageBody = '';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/styles.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php if ($showSidebar): ?>
        <div class="dashboard-shell d-flex">
            <aside class="dashboard-sidebar border-end bg-white shadow-sm">
                <div class="sidebar-brand p-4 text-center border-bottom">
                    <h5 class="mb-0">Admin Dashboard</h5>
                    <small class="text-muted">Sistema de usuários</small>
                </div>
                <nav class="nav flex-column p-3">
                    <a class="nav-link <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" href="/admin">Dashboard</a>
                    <a class="nav-link <?= $activeMenu === 'users' ? 'active' : '' ?>" href="/admin/users">Usuários</a>
                </nav>
            </aside>
            <main class="dashboard-content flex-grow-1">
                <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
                    <div class="container-fluid px-4">
                        <button class="btn btn-outline-secondary d-lg-none" type="button" id="sidebarToggle">Menu</button>
                        <span class="navbar-brand mb-0 h1"><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></span>
                        <div>
                            <a href="/" class="btn btn-sm btn-outline-primary">Área pública</a>
                        </div>
                    </div>
                </nav>
                <div class="container-fluid px-4 py-4">
                    <?php if (!empty($breadcrumbs)): ?>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <?php foreach ($breadcrumbs as $item): ?>
                                    <?php if (isset($item['url'])): ?>
                                        <li class="breadcrumb-item"><a href="<?= htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></a></li>
                                    <?php else: ?>
                                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ol>
                        </nav>
                    <?php endif; ?>
                    <?= $pageBody ?>
                </div>
            </main>
        </div>
    <?php else: ?>
        <main class="container py-5">
            <?= $pageBody ?>
        </main>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/app.js" defer></script>
</body>
</html>
