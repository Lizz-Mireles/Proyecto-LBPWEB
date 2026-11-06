<?php
include("sidebar.php");
require_once("_db.php");
$id = $_GET['id'];
$sql = "SELECT * FROM usuarios WHERE id=$id";
 
$resultado = $conexion->query($sql);
if($resultado->num_rows == 0){
    echo "Usuario no encontrado.";
    exit();
}
$usuario = $resultado->fetch_assoc();
?>
<div class="contenido">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Editar usuario</h2>
            <p class="text-muted mb-0">
                Modificar información del usuario
            </p>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-header bg-warning">
            <i class="bi bi-pencil"></i>
            Datos del usuario
        </div>
        <div class="card-body">
            <form action="actualizar_usuario.php" method="POST">
                <input type="hidden"
                       name="id"
                       value="<?php echo $usuario['id']; ?>">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text"
                               name="nombre"
                               class="form-control"
                               value="<?php echo $usuario['nombre']; ?>"
                               maxlength="100"
                               required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text"
                               name="username"
                               class="form-control"
                               value="<?php echo $usuario['username']; ?>"
                               maxlength="50"
                               required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            Nueva contraseña
                        </label>
                        <input type="password"
                               name="password"
                               class="form-control">
                        <small class="text-muted">
                            Dejar vacío para conservar la contraseña actual.
                        </small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rol</label>
                        <select name="rol" class="form-select" required>
                            <option value="admin"
                                <?php if($usuario['rol']=="admin") echo "selected"; ?>>
                                Administrador
                            </option>
                            <option value="tecnico"
                                <?php if($usuario['rol']=="tecnico") echo "selected"; ?>>
                                Técnico
                            </option>
                            <option value="cliente"
                                <?php if($usuario['rol']=="cliente") echo "selected"; ?>>
                                Cliente
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hora de salida</label>
                        <input type="time"
                               name="hora_salida"
                               class="form-control"
                               value="<?php echo $usuario['hora_salida']; ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Estado</label>
                        <select name="activo" class="form-select">
                            <option value="1"
                                <?php if($usuario['activo']==1) echo "selected"; ?>>
                                Activo
                            </option>
                            <option value="0"
                                <?php if($usuario['activo']==0) echo "selected"; ?>>
                                Inactivo
                            </option>
                        </select>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i>
                    Guardar cambios
                </button>
                <a href="registro.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Cancelar
                </a>
            </form>
        </div>
    </div>
</div>
</body>
</html>