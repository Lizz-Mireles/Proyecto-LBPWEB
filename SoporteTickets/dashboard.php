<?php
include("sidebar.php");  
require_once("_db.php"); 
$rol = $_SESSION['usuario']['tipo'];  
$id_usuario = $_SESSION['usuario']['id']; 
$where = "where 1=1";
if($rol=="cliente"){
    $where = "WHERE creado_por= $id_usuario";
}
if($rol=="tecnico"){
    $where = "WHERE asignado_a=$id_usuario";
}
// Tickets Abiertos
$sql = "SELECT COUNT(*) as total from tickets $where and estado='abierto'";
$resultado = $conexion->query($sql);
$abiertos = $resultado->fetch_assoc()['total'];
// tickets en Proceso
$sql = "SELECT COUNT(*) as total FROM tickets $where AND estado='proceso'";
$resultado = $conexion->query($sql);
$proceso = $resultado->fetch_assoc()['total'];
// Tickets Cerrados
$sql = "SELECT COUNT(*) as total FROM tickets $where AND estado='cerrado'";
$resultado = $conexion->query($sql);
$cerrados = $resultado->fetch_assoc()['total'];
//Tickets Criticos
$sql = "SELECT COUNT(*) as total FROM tickets $where AND prioridad='critica'";
$resultado = $conexion->query($sql);
$criticos = $resultado->fetch_assoc()['total'];
// Ultimos tickets
$sqlTickets = "
SELECT
folio,
titulo,
prioridad,
estado, fecha_creacion
FROM tickets $where order by fecha_creacion DESC LIMIT 5";
$tickets = $conexion->query($sqlTickets);
?>
<div class="contenido">
    <div class="d-flex justify-content-between align-items-center mb-4">
       <div>
        <h2>Dashboard</h2>
        <p class="text-muted mb-0">Bienvenido
            <strong><?php echo $_SESSION['usuario']['nombre'] ?></strong>
        </p>
       </div>
       <div>
        <span class="badge bg-primary fs-6">
            Rol:
            <?php echo strtoupper($rol); ?>
        </span>
       </div>
    </div>
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-ticket-perforated"></i>
                        Tickets Abiertos
                    </h5>
                    <h1 class="text-primary">
                        <?php echo $abiertos;?>
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-hourglass-split"></i>
                        En proceso
                    </h5>
                    <h1 class="text-warning">
                        <?php echo $proceso; ?>
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-success shadow">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-check-circle"></i>
                        Cerrados
                    </h5>
                    <h1 class="text-success">
                        <?php echo $cerrados; ?>
                    </h1>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-danger shadow">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-exclamation-circle"></i>
                        Criticos
                    </h5>
                    <h1 class="text-danger">
                        <?php echo $criticos; ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-clock-history"></i>
            Ultimos Tickets
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
                <tr>
                  <th>
                        Folio
                    </th>
                    <th>
                        Titulo
                    </th>
                    <th>
                        Prioridad
                    </th>
                    <th>
                        Estado
                    </th>
                    <th>
                        Fecha
                    </th>
                </tr>    
            </thead>
            <tbody>
                <?php if($tickets->num_rows>0){?>
                    <?php while($fila=$tickets->fetch_assoc()){?>
                        <tr>
                            <td>
                                <?php echo $fila['folio'];?>
                            </td>
                            <td>
                                <?php echo $fila['titulo'];?>
                            </td>
                            <td>
                                <?php echo strtoupper($fila['prioridad']);?>
                            </td>
                            <td>
                                <?php echo strtoupper($fila['estado']);?>
                            </td>
                            <td>
                                <?php echo $fila['fecha_creacion'];?>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else{?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    No existen tickets registrados
                                </td>
                            </tr>
                        <?php } ?>    
            </tbody>
            </table>
        </div>
    </div>
</div>
