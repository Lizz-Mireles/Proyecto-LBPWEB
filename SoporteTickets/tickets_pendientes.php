<?php
include("sidebar.php");
require_once("_db.php");
$rol=$_SESSION['usuario']['tipo'];
$id_usuario=$_SESSION['usuario']['id'];

$where="where 1=1";
if($rol=="cliente"){
    $where="where t.creado_por = $id_usuario";
}elseif($rol=="tecnico"){
    $where="where t.asignado_a= $id_usuario";
}
$sql="
SELECT
t.id,
t.folio,
t.titulo,
t.prioridad,
t.estado,
t.fecha_creacion,
u.nombre AS tecnico
FROM tickets t
LEFT JOIN usuarios u
ON t.asignado_a= u.id
$where
ORDER BY t.fecha_creacion DESC";
$tickets=$conexion->query($sql);
?>

<div class="contenido">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-ticket-perforated"></i>Tickets
        </h2>
        <?php if($rol=="cliente" || $rol=="admin"){?>
            <a href="nuevo_ticket.php">
            <i class="bi bi-plus-circle"></i>Nuevo Ticket
            </a>
        <?php } ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table bordered align-middle">
                <thead class="table-ligth">
                    <tr>
                        <th>Folio</th>
                        <th>Titulo</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Tecnico</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($tickets->num_rows>0){?>
                       <?php while($ticket=$tickets->fetch_assoc()){?>
                           <tr>
                              <td>
                                <?php echo $ticket['folio'];?>
                              </td>
                              <td>
                                <?php echo $ticket['titulo'];?>
                              </td>
                              <td>
                                <?php $color = "secondary";
                                  if($ticket['prioridad'] == "baja"){
                                    $color = "success";
                                  }
                                  if($ticket['prioridad'] == "media"){
                                    $color = "warning";
                                  }
                                  if($ticket['prioridad'] == "alta"){
                                    $color = "danger";
                                  }
                                  if($ticket['prioridad'] == "critica"){
                                    $color = "dark";
                                  }
                                ?>
                                <span class="badge bg-<?php echo $color;?>">
                                    <?php echo strtoupper($ticket['prioridad']);?>
                                </span>
                              </td>
                              <td>
                                <?php 
                                   $estadocolor = "secondary";
                                   if($ticket['estado']=="abierto"){
                                    $estadoColor = "primary";
                                   }
                                   if($ticket['estado']=="proceso"){
                                    $estadoColor = "warning";
                                   }
                                   if($ticket['estado']=="cerrado"){
                                    $estadoColor = "success";
                                   }
                                ?>
                                <span class="badge bg-<?php echo $estadoColor; ?>">
                                    <?php echo strtoupper($ticket['estado']);?>
                                </span>
                              </td>
                              <td>
                                <?php
                                echo $ticket['tecnico']?? 'Sin Asignar';
                                ?>
                              </td>
                              <td>
                                <?php
                                echo $ticket['fecha_creacion'];
                                ?>
                              </td>
                           </tr>
                           <?php } ?>
                           <?php }else{?>
                                <tr>
                                    <td colspan ="6" class="text-center text-muted">
                                        No hay tickets registrados
                                    </td>
                                </tr>
                            <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>