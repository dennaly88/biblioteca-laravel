<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('books.index') }}">📚 Biblioteca</a>
        <div>
            <a class="btn btn-sm btn-outline-light" href="{{ route('books.index') }}">Libros</a>
            <a class="btn btn-sm btn-outline-light" href="{{ route('categories.index') }}">Categorías</a>
        </div>
    </div>
</nav>
<main class="container">
    @yield('content')
</main>

<!-- Modal de mensajes -->
<div class="modal fade" id="msgModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" id="msgHeader"><h5 class="modal-title" id="msgTitle"></h5></div>
      <div class="modal-body" id="msgBody" style="white-space: pre-line"></div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Aceptar</button></div>
    </div>
  </div>
</div>

<!-- Modal de confirmación -->
<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Confirmar eliminación</h5></div>
      <div class="modal-body" id="confirmBody"></div>
      <div class="modal-footer">
        <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmYes">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const msgModalEl = document.getElementById('msgModal');
const msgModal = new bootstrap.Modal(msgModalEl);
const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
let redirectTo = null;

function showMsg(text, ok = true, redirect = null) {
    redirectTo = redirect;
    document.getElementById('msgHeader').className = 'modal-header ' + (ok ? 'bg-success text-white' : 'bg-danger text-white');
    document.getElementById('msgTitle').textContent = ok ? 'Listo' : 'Atención';
    document.getElementById('msgBody').textContent = text;
    msgModal.show();
    if (ok && redirect) setTimeout(() => msgModal.hide(), 1500);
}

msgModalEl.addEventListener('hidden.bs.modal', () => { if (redirectTo) window.location.href = redirectTo; });

async function send(url, method, data) {
    const r = await fetch(url, {
        method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: data ? JSON.stringify(data) : undefined
    });
    let json = {};
    try { json = await r.json(); } catch (e) {}
    return { ok: r.ok, status: r.status, json };
}

function errorText(res) {
    if (res.status === 422 && res.json.errors) return Object.values(res.json.errors).flat().join('\n');
    return res.json.message || 'Ocurrió un error inesperado.';
}

// Formularios de crear / editar
document.querySelectorAll('form[data-ajax]').forEach(form => {
    form.addEventListener('submit', async e => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(form));
        delete data._token; delete data._method;
        const res = await send(form.action, form.dataset.method || 'POST', data);
        if (res.ok) showMsg(form.dataset.success, true, form.dataset.redirect);
        else showMsg(errorText(res), false);
    });
});

// Botones de eliminar
document.querySelectorAll('.js-delete').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('confirmBody').textContent = '¿Seguro que deseas eliminar "' + btn.dataset.name + '"?';
        const yes = document.getElementById('confirmYes');
        yes.onclick = async () => {
            confirmModal.hide();
            const res = await send(btn.dataset.url, 'DELETE');
            if (res.ok) showMsg(res.json.message, true, window.location.href);
            else showMsg(errorText(res), false);
        };
        confirmModal.show();
    });
});

// Mensajes flash del servidor (por si acaso)
@if (session('success')) showMsg(@json(session('success')), true); @endif
@if (session('error')) showMsg(@json(session('error')), false); @endif
</script>
</body>
</html>
