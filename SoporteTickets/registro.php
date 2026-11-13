<?php
include("sidebar.php");
require_once("_db.php");
$sql= "SELECT id, nombre, username, rol, hora_salida, activo, created_at
FROM usuarios ORDER BY id DESC";
$resultado= $conexion->query($sql);
?>
<div class= "contenido">
    <div class= "d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Usuarios</h2>
            <p class="text-muted mb-o">
                Administracion de usuarios del sistema
            </p>
        </div>
        <div>
            <a href="nuevo_usuario.php" class="btn btn-primary">
                <i class="bi bi-person-plus"></i>Nuevo Usuario
            </a>
        </div>
    </div>
    <div class= "card-shadow">
        <div class="card-header bg-dark text-white">
           <i class="bi bi-people"></i>Lista de usuarios
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Usuario</th>
                      <th>Rol</th>
                      <th>Hora Salida</th>
                      <th>Estado</th>
                      <th>Fecha creacion</th>
                      <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($resultado->num_rows>0){?>
                       <?php while($usuario=$resultado->fetch_assoc()){?>
                           <tr>
                               <td>
                                  <?php echo $usuario['id'];?>
                               </td>
                               <td>
                                  <?php echo $usuario['nombre'];?>
                               </td>
                               <td>
                                  <?php echo $usuario['username'];?>
                               </td>
                               <td>
                                  <?php echo $usuario['rol'];?>
                               </td>
                               <td>
                                  <?php echo $usuario['hora_salida'];?>
                               </td>
                               <td>
                                  <?php if($usuario['activo']==1){?>
                                     <span class="badge bg-success">Activo</span>
                                     <?php } else{ ?>
                                        <span class="badge bg-danger">Inactivo</span>
                                        <?php } ?>
                               </td>
                               <td>
                                  <?php echo $usuario['created_at'];?>
                               </td>
                               <td>
                                  <a href="editar_usuario.php?id=<?php echo $usuario['id'];?>
                                  " class="btn btn-warning btn-sm">
                                         <i class="bi bi-pencil"></i>Editar
                                  </a>
                                  <a href="eliminar_usuario.php?id=<?php echo $usuario['id']; ?>"
                                  class="btn btn-denger btn-sm" onclick="return confirm(
                                  'Esta seguro de eliminar este usuario?');">
                                    <i class="bi bi-trash"></i>Eliminar
                                  </a>
                               </td>
                           </tr>
                        <?php } ?>
                    <?php } ?> 
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No existen usuarios registrados
                        </td>
                    </tr>  
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
