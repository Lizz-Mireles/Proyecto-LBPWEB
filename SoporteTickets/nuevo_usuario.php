<?php
include('sidebar.php');
require_once("_db.php");?>
<div class="contenido">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Nuevo Usuario</h2>
            <p class="text-muted mb-0">
                Registrar un nuevo usuario 
            </p>
        </div>
    </div>
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-person-plus"></i>Datos del Usuario
            </div>
            <div class="card-body">
                <form action="guardar_usuario.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre"
                            class="form-control" maxlength="100" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" name="username" id="username"
                            class="form-control" maxlength="100" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Contrasena</label>
                            <input type="password" class="form-control" name="password"
                            id="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="rol" class="form-label">Rol</label>
                            <select name="rol" id="rol" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="admin">Administrador</option>
                                <option value="tecnico">Tecnico</option>
                                <option value="cliente">Cliente</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="hora_salida" class="form-label">Hora de Salida</label>
                            <input type="time" class="form-control" name="hora_salida"
                            id="hora_salida" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="activo" class="form-label">Estado</label>
                            <select name="activo" id="activo" class="form-select" required>
                                <option value="">Seleccione...</option>
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i>Guardar Usuario
                    </button>
                    <a href="registro.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i>Cancelar
                    </a>
                </form>
            </div>
        </div>
</div>
</body>
</html>