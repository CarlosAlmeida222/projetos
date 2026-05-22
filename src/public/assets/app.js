const API_URL = '/api/users';
let currentPage = 1;
let currentSearch = '';
let lastPage = 1;

const searchInput = document.getElementById('search');
const paginationElement = document.getElementById('pagination');
const userTableBody = document.querySelector('#userTable tbody');
const alertsContainer = document.getElementById('alerts');
const userModal = new bootstrap.Modal(document.getElementById('userModal'));
const userForm = document.getElementById('userForm');
const deleteButton = document.getElementById('btnDelete');
const modalTitle = document.getElementById('userModalLabel');
const userIdInput = document.getElementById('userId');

const userFields = {
    name: document.getElementById('userName'),
    email: document.getElementById('userEmail'),
    phone: document.getElementById('userPhone'),
    status: document.getElementById('userStatus'),
    password: document.getElementById('userPassword'),
};

function showMessage(message, type = 'success') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-fade`;
    alert.setAttribute('role', 'alert');
    alert.innerHTML = message;
    alertsContainer.appendChild(alert);
    setTimeout(() => alert.remove(), 4200);
}

function showFormMessage(message, type = 'danger') {
    const container = document.getElementById('formAlerts');
    if (!container) {
        showMessage(message, type);
        return;
    }
    container.innerHTML = `<div class="alert alert-${type} mb-3" role="alert">${message}</div>`;
}

function debounce(fn, delay = 400) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

async function fetchUsers(page = 1, search = '') {
    const params = new URLSearchParams({page, perPage: 10, search});
    const response = await fetch(`${API_URL}?${params.toString()}`);
    return response.json();
}

function renderPagination(page, totalPages) {
    currentPage = page;
    lastPage = totalPages;
    const buttons = [];
    buttons.push({label: 'Primeira', page: 1, disabled: page === 1});
    buttons.push({label: 'Anterior', page: Math.max(1, page - 1), disabled: page === 1});

    const visible = 5;
    let start = Math.max(1, page - Math.floor(visible / 2));
    let end = Math.min(totalPages, start + visible - 1);
    start = Math.max(1, end - visible + 1);

    for (let value = start; value <= end; value += 1) {
        buttons.push({label: value, page: value, active: value === page});
    }

    buttons.push({label: 'Próxima', page: Math.min(totalPages, page + 1), disabled: page === totalPages});
    buttons.push({label: 'Última', page: totalPages, disabled: page === totalPages});

    paginationElement.innerHTML = buttons.map(button => {
        const disabled = button.disabled ? ' disabled' : '';
        const active = button.active ? ' active' : '';
        return `<li class="page-item${disabled}${active}"><button class="page-link" data-page="${button.page}" ${disabled ? 'tabindex="-1"' : ''}>${button.label}</button></li>`;
    }).join('');
}

function openModal(mode, user = null) {
    if (mode === 'create') {
        modalTitle.textContent = 'Adicionar novo usuário';
        deleteButton.classList.add('d-none');
        userIdInput.value = '';
        userForm.reset();
    } else {
        modalTitle.textContent = 'Editar usuário';
        deleteButton.classList.remove('d-none');
        userIdInput.value = user.id;
        userFields.name.value = user.name || '';
        userFields.email.value = user.email || '';
        userFields.phone.value = user.phone || '';
        userFields.status.value = user.status || 'active';
        userFields.password.value = '';
    }
    userModal.show();
    // clear form-specific alerts
    const formAlerts = document.getElementById('formAlerts');
    if (formAlerts) formAlerts.innerHTML = '';
}

async function loadUsers(page = 1, search = '') {
    userTableBody.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-muted">Carregando...</td></tr>';
    const result = await fetchUsers(page, search);
    if (!result.data || result.data.length === 0) {
        userTableBody.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-muted">Nenhum registro encontrado.</td></tr>';
        renderPagination(1, 1);
        return;
    }

    userTableBody.innerHTML = result.data.map(user => `
        <tr data-id="${user.id}">
            <td>${user.id}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.status}</td>
            <td>${user.created_at}</td>
        </tr>
    `).join('');

    renderPagination(result.page, result.totalPages);
    syncTableRowClicks();
}

async function fetchUser(id) {
    const response = await fetch(`${API_URL}/${id}`);
    return response.json();
}

async function saveUser(data) {
    const userId = data.id;
    const method = userId ? 'PUT' : 'POST';
    const url = userId ? `${API_URL}/${userId}` : API_URL;
    const response = await fetch(url, {
        method,
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data),
    });
    return response.json();
}

async function deleteUser(id) {
    const response = await fetch(`${API_URL}/${id}`, {method: 'DELETE'});
    return response.json();
}

function syncTableRowClicks() {
    userTableBody.querySelectorAll('tr[data-id]').forEach(row => {
        row.addEventListener('click', async () => {
            const id = row.dataset.id;
            const result = await fetchUser(id);
            if (result.data) {
                openModal('edit', result.data);
            } else {
                showMessage('Falha ao carregar usuário.', 'danger');
            }
        });
    });
}

function bindEvents() {
    document.getElementById('btnAdd').addEventListener('click', () => openModal('create'));
    searchInput.addEventListener('input', debounce(async event => {
        currentSearch = event.target.value.trim();
        await loadUsers(1, currentSearch);
        syncTableRowClicks();
    }));

    paginationElement.addEventListener('click', async event => {
        const button = event.target.closest('button[data-page]');
        if (!button) {
            return;
        }
        const page = Number(button.dataset.page);
        if (!page || page === currentPage || page < 1 || page > lastPage) {
            return;
        }
        await loadUsers(page, currentSearch);
        syncTableRowClicks();
    });

    userForm.addEventListener('submit', async event => {
        event.preventDefault();
        const formData = {
            id: userIdInput.value || null,
            name: userFields.name.value.trim(),
            email: userFields.email.value.trim(),
            phone: userFields.phone.value.trim(),
            status: userFields.status.value,
            password: userFields.password.value,
        };
        const result = await saveUser(formData);
        if (result.error) {
            showFormMessage(result.error);
            return;
        }
        showMessage(result.message || 'Salvo com sucesso.');
        userModal.hide();
        await loadUsers(currentPage, currentSearch);
        syncTableRowClicks();
    });

    deleteButton.addEventListener('click', async () => {
        const id = userIdInput.value;
        if (!id || !confirm('Tem certeza que deseja excluir este usuário?')) {
            return;
        }
        const result = await deleteUser(id);
        if (result.error) {
            showMessage(result.error, 'danger');
            return;
        }
        showMessage(result.message || 'Excluído com sucesso.');
        userModal.hide();
        await loadUsers(currentPage, currentSearch);
        syncTableRowClicks();
    });
}

function initDashboard() {
    if (!searchInput || !paginationElement || !userTableBody) {
        return;
    }
    bindEvents();
    loadUsers(1, '');
    userTableBody.addEventListener('DOMSubtreeModified', syncTableRowClicks);
}

document.addEventListener('DOMContentLoaded', initDashboard);
