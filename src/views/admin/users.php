<?php

$pageTitle = 'Usuários';
$breadcrumbs = [
    ['label' => 'Admin', 'url' => '/admin'],
    ['label' => 'Usuários'],
];
$activeMenu = 'users';
ob_start();
?>
<div class="d-flex flex-column gap-4">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1">Lista de usuários</h2>
                            <p class="text-muted mb-0">Gerencie os perfis e acessos da área administrativa.</p>
                        </div>
                        <button id="btnAdd" class="btn btn-primary">Adicionar novo usuário</button>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text">Pesquisar</span>
                                <input id="search" type="search" class="form-control" placeholder="Nome, email ou status" aria-label="Pesquisar usuários">
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="text-muted">Resultados atualizados via AJAX.</div>
                        </div>
                    </div>
                    <div id="alerts"></div>
                    <div class="table-responsive shadow-sm rounded-3 border">
                        <table class="table table-hover mb-0 align-middle" id="userTable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Cadastro</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="5" class="text-center py-5 text-muted">Carregando...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <nav aria-label="Navegação de página" class="mt-3">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 bg-white p-4">
                <h3 class="h6 mb-3">Detalhes rápidos</h3>
                <p class="text-muted">Clique em uma linha para abrir o formulário de edição. O botão acima adiciona um novo registro.</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Páginas ativas
                        <span class="badge bg-secondary">5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        AJAX completo
                        <span class="badge bg-secondary">Sim</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Layout responsivo
                        <span class="badge bg-secondary">Bootstrap 5</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="userForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Novo usuário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div id="formAlerts"></div>
                    <input type="hidden" id="userId" name="id" value="">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="userName">Nome</label>
                            <input id="userName" name="name" type="text" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="userEmail">Email</label>
                            <input id="userEmail" name="email" type="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="userPhone">Telefone</label>
                            <input id="userPhone" name="phone" type="text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="userStatus">Status</label>
                            <select id="userStatus" name="status" class="form-select">
                                <option value="active">Ativo</option>
                                <option value="inactive">Inativo</option>
                                <option value="blocked">Bloqueado</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="userPassword">Senha</label>
                            <input id="userPassword" name="password" type="password" class="form-control" placeholder="Deixe em branco para manter a senha">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" id="btnDelete">Excluir</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$pageBody = ob_get_clean();
require __DIR__ . '/../layout.php';
