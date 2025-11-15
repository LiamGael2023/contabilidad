<div class="row">
    <div class="col-12">
        <h1><i class="bi bi-person-plus"></i> Nuevo Usuario</h1>
        <hr>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/usuarios/guardar">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                    <h5 class="card-title mb-3">Datos de Acceso</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Nombre de Usuario *</label>
                            <input type="text" class="form-control" id="username" name="username"
                                   required maxlength="50" autocomplete="off">
                            <small class="text-muted">Solo letras, números y guión bajo</small>
                        </div>
                        <div class="col-md-6">
                            <label for="rol" class="form-label">Rol *</label>
                            <select class="form-select" id="rol" name="rol" required>
                                <option value="">Seleccione...</option>
                                <option value="admin">Administrador</option>
                                <option value="contador">Contador</option>
                                <option value="asistente">Asistente Contable</option>
                                <option value="consulta">Solo Consulta</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña *</label>
                            <input type="password" class="form-control" id="password" name="password"
                                   required minlength="6" autocomplete="new-password">
                            <small class="text-muted">Mínimo 6 caracteres</small>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirm" class="form-label">Confirmar Contraseña *</label>
                            <input type="password" class="form-control" id="password_confirm"
                                   name="password_confirm" required minlength="6">
                        </div>
                    </div>

                    <h5 class="card-title mb-3 mt-4">Datos Personales</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre"
                                   required maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido *</label>
                            <input type="text" class="form-control" id="apellido" name="apellido"
                                   required maxlength="100">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email"
                               required maxlength="100">
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Permisos por Rol:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Administrador:</strong> Acceso total al sistema</li>
                            <li><strong>Contador:</strong> Gestión contable completa excepto configuración</li>
                            <li><strong>Asistente Contable:</strong> Registro de asientos y consultas</li>
                            <li><strong>Solo Consulta:</strong> Ver reportes y libros sin modificar</li>
                        </ul>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="/usuarios" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">
                            <i class="bi bi-save"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Validar que las contraseñas coincidan
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    const btnGuardar = document.getElementById('btnGuardar');

    if (password !== confirm) {
        this.setCustomValidity('Las contraseñas no coinciden');
        this.classList.add('is-invalid');
        btnGuardar.disabled = true;
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
        btnGuardar.disabled = false;
    }
});

document.getElementById('password').addEventListener('input', function() {
    const confirm = document.getElementById('password_confirm');
    if (confirm.value) {
        confirm.dispatchEvent(new Event('input'));
    }
});

// Validar formato de username (solo letras, números y guión bajo)
document.getElementById('username').addEventListener('input', function() {
    const regex = /^[a-zA-Z0-9_]*$/;
    if (!regex.test(this.value)) {
        this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
    }
});
</script>
