<?php
session_start();
require_once("_db.php");
//if(isset($_SESSION['usuario'])){
//    header("Location: index.php");
//   exit();
//}
$creado_por = $_SESSION['usuario']['id'];
$tipo_reporte = $_POST['tipo_reporte'];
$prioridad = $_POST['prioridad'];
$descripcion = $_POST['descripcion'];
$departamento = $_POST['departamento'];
$ubicacion = $_POST['ubicacion'];
$inventario = $_POST['inventario'];
$anydesk = $_POST['anydesk'];
$quien_reporta = $_POST['quien_reporta'];
$telefono = $_POST['telefono'];
$extension = $_POST['extension'];
$titulo = $_POST['titulo'];
$estado = "abierto";
$tecnicos = [];
$sqlTecnicos = "
select u.id, count(t.id) as total
FROM usuarios u
LEFT JOIN tickets t
on u.id = t.asignado_a
AND DATE(t.fecha_creacion) = CURDATE()
WHERE u.rol = 'tecnico' and u.activo = 1
GROUP BY u.id ORDER by total ASC";
$resultadoTecnicos = $conexion->query($sqlTecnicos);
$menorCarga = null;
while($fila = $resultadoTecnicos->fetch_assoc()){
    if($menorCarga === null){
        $menorCarga = $fila['total'];
    }
    if($fila['total']==$menorCarga){
        $tecnicos[]=$fila['id'];
    }
}
$asignado_a = null;
if(count($tecnicos)>0){
    $asignado_a = $tecnicos[array_rand($tecnicos)];
}
$sql= "SELECT MAX(id) as ultimo FROM tickets";
$resultado = $conexion->query($sql);
$fila = $resultado->fetch_assoc();
$siguiente = $fila['ultimo'] + 1;
if(empty($fila['ultimo'])){
    $siguiente = 1;
}
$folio = "TK-" . str_pad($siguiente, 6, "0", STR_PAD_LEFT);
$sql="INSERT INTO tickets(
folio,
titulo,
prioridad,
creado_por,
asignado_a,
estado,
tipo_reporte,
inventario,
anydesk,
departamento,
ubicacion,
telefono,
extension,
quien_reporta)
VALUES(
'$folio',
'$titulo',
'$prioridad',
'$creado_por',
".($asignado_a===NULL ? "NULL" : $asignado_a).",
'$estado',
'$tipo_reporte',
'$inventario',
'$anydesk',
'$departamento',
'$ubicacion',
'$telefono',
'$extension',
'$quien_reporta')";
if($conexion->query($sql)){
    header("Location: nuevo_ticket.php?ok=1");
    exit();
}else{
    echo"<h3>Error al guardar el ticket</h3>";
    echo($conexion->error);
}
$conexion->close();
?>