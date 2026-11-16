<?php
include("sidebar.php");
require_once("_db.php");
require_once("_filtros_reportes.php");

$rol = $_SESSION['usuario']['tipo'];
if($rol == "cliente"){
    header("Location: dashboard.php");
    exit();
}
list($where, $types, $params) = construirFiltros();

$resumen = consulta($conexion, "
    SELECT
        COUNT(*) AS total,
        SUM(t.estado='abierto') AS abiertos,
        SUM(t.estado='proceso') AS proceso,
        SUM(t.estado='espera') AS espera,
        SUM(t.estado='cerrado') AS cerrados,
        SUM(t.prioridad='critica') AS criticos,
        AVG(CASE WHEN t.estado='cerrado' AND t.fecha_cierre IS NOT NULL
            THEN TIMESTAMPDIFF(MINUTE, t.fecha_creacion, t.fecha_cierre) END) AS promedio
    FROM tickets t
    WHERE $where", $types, $params)[0];
$total    = (int)$resumen['total'];
$abiertos = (int)$resumen['abiertos'];
$proceso  = (int)$resumen['proceso'];
$espera   = (int)$resumen['espera'];
$cerrados = (int)$resumen['cerrados'];
$criticos = (int)$resumen['criticos'];
$tasaCierre = $total > 0 ? round(($cerrados / $total) * 100) : 0;

$porEstado = consulta($conexion, "
    SELECT t.estado AS etiqueta, COUNT(*) AS total
    FROM tickets t WHERE $where GROUP BY t.estado", $types, $params);
$porPrioridad = consulta($conexion, "
    SELECT t.prioridad AS etiqueta, COUNT(*) AS total
    FROM tickets t WHERE $where GROUP BY t.prioridad", $types, $params);
$porTipo = consulta($conexion, "
    SELECT IF(t.tipo_reporte IS NULL OR t.tipo_reporte='', 'Sin tipo', t.tipo_reporte) AS etiqueta,
           COUNT(*) AS total
    FROM tickets t WHERE $where GROUP BY etiqueta ORDER BY total DESC", $types, $params);
$porTecnico = [];
if($rol == "admin"){
    $porTecnico = consulta($conexion, "
        SELECT COALESCE(u.nombre, 'Sin asignar') AS tecnico,
               COUNT(*) AS total,
               SUM(t.estado='cerrado') AS cerrados,
               SUM(t.estado<>'cerrado') AS pendientes,
               AVG(CASE WHEN t.estado='cerrado' AND t.fecha_cierre IS NOT NULL
                   THEN TIMESTAMPDIFF(MINUTE, t.fecha_creacion, t.fecha_cierre) END) AS promedio
        FROM tickets t
        LEFT JOIN usuarios u ON t.asignado_a = u.id
        WHERE $where
        GROUP BY t.asignado_a, u.nombre
        ORDER BY total DESC", $types, $params);
}

$detalle = consulta($conexion, "
    SELECT t.folio, t.titulo, t.tipo_reporte, t.prioridad, t.estado,
           t.fecha_creacion, t.fecha_cierre,
           c.nombre AS creador, u.nombre AS tecnico
    FROM tickets t
    LEFT JOIN usuarios u ON t.asignado_a = u.id
    LEFT JOIN usuarios c ON t.creado_por = c.id
    WHERE $where
    ORDER BY t.fecha_creacion DESC", $types, $params);

$listaTecnicos = $conexion->query("SELECT id, nombre FROM usuarios WHERE rol='tecnico' ORDER BY nombre")->fetch_all(MYSQLI_ASSOC);
$listaTipos = $conexion->query("SELECT DISTINCT tipo_reporte FROM tickets WHERE tipo_reporte IS NOT NULL AND tipo_reporte<>'' ORDER BY tipo_reporte")->fetch_all(MYSQLI_ASSOC);

$f_desde = $_GET['desde'] ?? '';
$f_hasta = $_GET['hasta'] ?? '';
$f_estado = $_GET['estado'] ?? '';
$f_prioridad = $_GET['prioridad'] ?? '';
$f_tipo = $_GET['tipo_reporte'] ?? '';
$f_tecnico = $_GET['tecnico'] ?? '';

$queryString = http_build_query(array_filter([
    'desde' => $f_desde, 'hasta' => $f_hasta, 'estado' => $f_estado,
    'prioridad' => $f_prioridad, 'tipo_reporte' => $f_tipo, 'tecnico' => $f_tecnico
], function($v){ return $v !== ''; }));

function colorPrioridad($p){
    if($p == "baja") return "success";
    if($p == "media") return "warning";
    if($p == "alta") return "danger";
    if($p == "critica") return "dark";
    return "secondary";
}
function colorEstado($s){
    if($s == "abierto") return "primary";
    if($s == "proceso") return "warning";
    if($s == "cerrado") return "success";
    if($s == "espera") return "info";
    return "secondary";
}
?>
<style>
    @media print{
        .sidebar, .no-print{ display:none !important; }
        .contenido{ margin-left:0 !important; width:100% !important; padding:0 !important; }
        .card{ break-inside: avoid; box-shadow:none !important; }
    }
</style>

<div class="contenido">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="bi bi-graph-up"></i>Reportes
            </h2>
            <p class="text-muted mb-0">
                <?php echo $rol == "admin" ? "Resumen general de tickets del sistema" : "Resumen de tus tickets asignados"; ?>
            </p>
        </div>
        <div class="no-print">
            <a href="exportar_reportes.php?<?php echo e($queryString); ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-spreadsheet"></i>Exportar CSV
            </a>
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="bi bi-printer"></i>Imprimir / PDF
            </button>
        </div>
    </div>

    <div class="card shadow mb-4 no-print">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-funnel"></i>Filtros
        </div>
        <div class="card-body">
            <form method="GET" action="reportes.php">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="desde" class="form-label">Desde</label>
                        <input type="date" name="desde" id="desde" class="form-control" value="<?php echo e($f_desde); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="hasta" class="form-label">Hasta</label>
                        <input type="date" name="hasta" id="hasta" class="form-control" value="<?php echo e($f_hasta); ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-select">
                            <option value="">Todos</option>
                            <option value="abierto" <?php if($f_estado=="abierto") echo "selected"; ?>>Abierto</option>
                            <option value="proceso" <?php if($f_estado=="proceso") echo "selected"; ?>>En proceso</option>
                            <option value="espera" <?php if($f_estado=="espera") echo "selected"; ?>>En espera</option>
                            <option value="cerrado" <?php if($f_estado=="cerrado") echo "selected"; ?>>Cerrado</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="prioridad" class="form-label">Prioridad</label>
                        <select name="prioridad" id="prioridad" class="form-select">
                            <option value="">Todas</option>
                            <option value="baja" <?php if($f_prioridad=="baja") echo "selected"; ?>>Baja</option>
                            <option value="media" <?php if($f_prioridad=="media") echo "selected"; ?>>Media</option>
                            <option value="alta" <?php if($f_prioridad=="alta") echo "selected"; ?>>Alta</option>
                            <option value="critica" <?php if($f_prioridad=="critica") echo "selected"; ?>>Critica</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="tipo_reporte" class="form-label">Tipo de reporte</label>
                        <select name="tipo_reporte" id="tipo_reporte" class="form-select">
                            <option value="">Todos</option>
                            <?php foreach($listaTipos as $t){ ?>
                                <option value="<?php echo e($t['tipo_reporte']); ?>"
                                    <?php if($f_tipo == $t['tipo_reporte']) echo "selected"; ?>>
                                    <?php echo e($t['tipo_reporte']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <?php if($rol == "admin"){ ?>
                        <div class="col-md-3 mb-3">
                            <label for="tecnico" class="form-label">Tecnico</label>
                            <select name="tecnico" id="tecnico" class="form-select">
                                <option value="">Todos</option>
                                <option value="sin" <?php if($f_tecnico=="sin") echo "selected"; ?>>Sin asignar</option>
                                <?php foreach($listaTecnicos as $t){ ?>
                                    <option value="<?php echo $t['id']; ?>"
                                        <?php if($f_tecnico == $t['id']) echo "selected"; ?>>
                                        <?php echo e($t['nombre']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i>Generar reporte
                </button>
                <a href="reportes.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i>Limpiar
                </a>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-lg-2 mb-4">
            <div class="card border-secondary shadow h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-collection"></i> Total</h6>
                    <h1 class="text-secondary"><?php echo $total; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2 mb-4">
            <div class="card border-primary shadow h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-ticket-perforated"></i> Abiertos</h6>
                    <h1 class="text-primary"><?php echo $abiertos; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2 mb-4">
            <div class="card border-warning shadow h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-hourglass-split"></i> En proceso</h6>
                    <h1 class="text-warning"><?php echo $proceso; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2 mb-4">
            <div class="card border-success shadow h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-check-circle"></i> Cerrados</h6>
                    <h1 class="text-success"><?php echo $cerrados; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2 mb-4">
            <div class="card border-danger shadow h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-exclamation-circle"></i> Criticos</h6>
                    <h1 class="text-danger"><?php echo $criticos; ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-2 mb-4">
            <div class="card border-info shadow h-100">
                <div class="card-body">
                    <h6 class="card-title"><i class="bi bi-percent"></i> Tasa de cierre</h6>
                    <h1 class="text-info"><?php echo $tasaCierre; ?>%</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-stopwatch fs-1 me-3 text-primary"></i>
                    <div>
                        <p class="text-muted mb-0">Tiempo promedio de resolucion</p>
                        <h3 class="mb-0"><?php echo formatoDuracion($resumen['promedio']); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-pause-circle fs-1 me-3 text-secondary"></i>
                    <div>
                        <p class="text-muted mb-0">Tickets en espera</p>
                        <h3 class="mb-0"><?php echo $espera; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-pie-chart"></i>Tickets por estado
                </div>
                <div class="card-body">
                    <?php if($total > 0){ ?>
                        <canvas id="graficaEstado"></canvas>
                    <?php } else { ?>
                        <p class="text-center text-muted mb-0">Sin datos</p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-bar-chart"></i>Tickets por prioridad
                </div>
                <div class="card-body">
                    <?php if($total > 0){ ?>
                        <canvas id="graficaPrioridad"></canvas>
                    <?php } else { ?>
                        <p class="text-center text-muted mb-0">Sin datos</p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-tags"></i>Tickets por tipo de reporte
                </div>
                <div class="card-body">
                    <?php if($total > 0){ ?>
                        <canvas id="graficaTipo"></canvas>
                    <?php } else { ?>
                        <p class="text-center text-muted mb-0">Sin datos</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php if($rol == "admin"){ ?>
    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-person-gear"></i>Desempeño por tecnico
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tecnico</th>
                            <th>Total</th>
                            <th>Cerrados</th>
                            <th>Pendientes</th>
                            <th>Tasa de cierre</th>
                            <th>Promedio de resolucion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($porTecnico) > 0){ ?>
                            <?php foreach($porTecnico as $fila){
                                $tasa = $fila['total'] > 0 ? round(($fila['cerrados'] / $fila['total']) * 100) : 0; ?>
                                <tr>
                                    <td><?php echo e($fila['tecnico']); ?></td>
                                    <td><?php echo (int)$fila['total']; ?></td>
                                    <td><span class="badge bg-success"><?php echo (int)$fila['cerrados']; ?></span></td>
                                    <td><span class="badge bg-warning"><?php echo (int)$fila['pendientes']; ?></span></td>
                                    <td style="min-width:150px;">
                                        <div class="progress">
                                            <div class="progress-bar bg-success" style="width: <?php echo $tasa; ?>%">
                                                <?php echo $tasa; ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo formatoDuracion($fila['promedio']); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No existen datos para mostrar</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-list-ul"></i>Detalle de tickets (<?php echo count($detalle); ?>)
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Folio</th>
                            <th>Titulo</th>
                            <th>Tipo</th>
                            <th>Prioridad</th>
                            <th>Estado</th>
                            <th>Creado por</th>
                            <th>Tecnico</th>
                            <th>Fecha creacion</th>
                            <th>Fecha cierre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($detalle) > 0){ ?>
                            <?php foreach($detalle as $fila){ ?>
                                <tr>
                                    <td><?php echo e($fila['folio']); ?></td>
                                    <td><?php echo e($fila['titulo']); ?></td>
                                    <td><?php echo e($fila['tipo_reporte']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo colorPrioridad($fila['prioridad']); ?>">
                                            <?php echo strtoupper(e($fila['prioridad'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo colorEstado($fila['estado']); ?>">
                                            <?php echo strtoupper(e($fila['estado'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo e($fila['creador'] ?? '-'); ?></td>
                                    <td><?php echo e($fila['tecnico'] ?? 'Sin Asignar'); ?></td>
                                    <td><?php echo e($fila['fecha_creacion']); ?></td>
                                    <td><?php echo e($fila['fecha_cierre'] ?? '-'); ?></td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">
                                    No existen tickets con los filtros seleccionados
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var porEstado = <?php echo json_encode($porEstado); ?>;
    var porPrioridad = <?php echo json_encode($porPrioridad); ?>;
    var porTipo = <?php echo json_encode($porTipo); ?>;
    var coloresEstado = { abierto:'#0d6efd', proceso:'#ffc107', espera:'#0dcaf0', cerrado:'#198754' };
    var coloresPrioridad = { baja:'#198754', media:'#ffc107', alta:'#dc3545', critica:'#212529' };

    function etiquetas(lista){ return lista.map(function(x){ return x.etiqueta.toUpperCase(); }); }
    function valores(lista){ return lista.map(function(x){ return parseInt(x.total); }); }
    function colores(lista, mapa){ return lista.map(function(x){ return mapa[x.etiqueta] || '#6c757d'; }); }

    if(document.getElementById('graficaEstado')){
        new Chart(document.getElementById('graficaEstado'), {
            type: 'doughnut',
            data: { labels: etiquetas(porEstado),
                    datasets: [{ data: valores(porEstado), backgroundColor: colores(porEstado, coloresEstado) }] },
            options: { plugins: { legend: { position: 'bottom' } } }
        });
        new Chart(document.getElementById('graficaPrioridad'), {
            type: 'bar',
            data: { labels: etiquetas(porPrioridad),
                    datasets: [{ label: 'Tickets', data: valores(porPrioridad), backgroundColor: colores(porPrioridad, coloresPrioridad) }] },
            options: { plugins: { legend: { display: false } },
                       scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
        new Chart(document.getElementById('graficaTipo'), {
            type: 'bar',
            data: { labels: porTipo.map(function(x){ return x.etiqueta; }),
                    datasets: [{ label: 'Tickets', data: valores(porTipo), backgroundColor: '#0d6efd' }] },
            options: { indexAxis: 'y', plugins: { legend: { display: false } },
                       scales: { x: { beginAtZero: true, ticks: { precision: 0 } } } }
        });
    }
</script>
</body>
</html>
