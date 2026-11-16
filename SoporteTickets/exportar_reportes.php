<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
if($_SESSION['usuario']['tipo'] == "cliente"){
    header("Location: dashboard.php");
    exit();
}

ob_start();
require_once("_db.php");
ob_end_clean();
require_once("filtros_reportes.php");

list($where, $types, $params) = construirFiltros();

$filas = consulta($conexion, "
    SELECT t.folio, t.titulo, t.descripcion, t.tipo_reporte, t.prioridad, t.estado,
           c.nombre AS creador, u.nombre AS tecnico,
           t.departamento, t.ubicacion, t.inventario, t.anydesk,
           t.quien_reporta, t.telefono, t.extension,
           t.fecha_creacion, t.fecha_asignacion, t.fecha_cierre, t.solucion
    FROM tickets t
    LEFT JOIN usuarios u ON t.asignado_a = u.id
    LEFT JOIN usuarios c ON t.creado_por = c.id
    WHERE $where
    ORDER BY t.fecha_creacion DESC", $types, $params);

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="reporte_tickets_' . date('Ymd_His') . '.csv"');

$salida = fopen('php://output', 'w');
fwrite($salida, "\xEF\xBB\xBF"); // BOM para que Excel respete los acentos

fputcsv($salida, [
    'Folio','Titulo','Descripcion','Tipo de reporte','Prioridad','Estado',
    'Creado por','Tecnico','Departamento','Ubicacion','Inventario','AnyDesk',
    'Quien reporta','Telefono','Extension',
    'Fecha creacion','Fecha asignacion','Fecha cierre','Solucion'
]);

foreach($filas as $f){
    fputcsv($salida, [
        $f['folio'], $f['titulo'], $f['descripcion'], $f['tipo_reporte'],
        strtoupper($f['prioridad']), strtoupper($f['estado']),
        $f['creador'], $f['tecnico'] ?? 'Sin asignar',
        $f['departamento'], $f['ubicacion'], $f['inventario'], $f['anydesk'],
        $f['quien_reporta'], $f['telefono'], $f['extension'],
        $f['fecha_creacion'], $f['fecha_asignacion'], $f['fecha_cierre'], $f['solucion']
    ]);
}
fclose($salida);
exit();
?>
