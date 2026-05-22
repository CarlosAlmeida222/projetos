<?php

$pageTitle = 'Bem-vindo';
$breadcrumbs = [];
$showSidebar = false;
ob_start();
?>
<section class="py-5 text-center">
    <div class="container">
        <h1 class="display-5 fw-bold">Projeto LAMP</h1>
        <p class="lead text-muted">Dashboard moderno de CRUD para usuários com Bootstrap 5 e AJAX.</p>
        <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
            <a href="/admin/users" class="btn btn-primary btn-lg px-4">Ir para área admin</a>
            <a href="/admin" class="btn btn-outline-secondary btn-lg px-4">Ver dashboard</a>
        </div>
    </div>
</section>
<section class="py-4 bg-white shadow-sm rounded-4 container">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="p-4 border rounded-3">
                <h3>Estrutura pronta</h3>
                <p>Sidebar, navbar fixa, breadcrumb dinâmico e área de conteúdo com tabela AJAX.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 border rounded-3">
                <h3>CRUD de usuários</h3>
                <p>Pesquisa, paginação, criação, edição e exclusão via AJAX sem recarregar a página.</p>
            </div>
        </div>
    </div>
</section>
<?php
$pageBody = ob_get_clean();
require __DIR__ . '/../layout.php';
