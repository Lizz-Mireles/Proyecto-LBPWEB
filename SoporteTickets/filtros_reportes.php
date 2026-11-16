<?php
function construirFiltros(){
    $rol = $_SESSION['usuario']['tipo'];
    $idUsuario = (int)$_SESSION['usuario']['id'];

    $w = ["1=1"];
    $types = "";
    $p = [];
  
    if($rol == "tecnico"){
        $w[] = "t.asignado_a = ?";
        $types .= "i";
        $p[] = $idUsuario;
    } elseif($rol == "cliente"){
        $w[] = "t.creado_por = ?";
        $types .= "i";
        $p[] = $idUsuario;
    }

    $desde = $_GET['desde'] ?? '';
    $hasta = $_GET['hasta'] ?? '';
    $estado = $_GET['estado'] ?? '';
    $prioridad = $_GET['prioridad'] ?? '';
    $tipo = $_GET['tipo_reporte'] ?? '';
    $tecnico = $_GET['tecnico'] ?? '';

    if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde)){
        $w[] = "DATE(t.fecha_creacion) >= ?";
        $types .= "s";
        $p[] = $desde;
    }
    if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta)){
        $w[] = "DATE(t.fecha_creacion) <= ?";
        $types .= "s";
        $p[] = $hasta;
    }
    if(in_array($estado, ['abierto','proceso','espera','cerrado'])){
        $w[] = "t.estado = ?";
        $types .= "s";
        $p[] = $estado;
    }
    if(in_array($prioridad, ['baja','media','alta','critica'])){
        $w[] = "t.prioridad = ?";
        $types .= "s";
        $p[] = $prioridad;
    }
    if($tipo !== ''){
        $w[] = "t.tipo_reporte = ?";
        $types .= "s";
        $p[] = $tipo;
    }

    if($rol == "admin" && $tecnico !== ''){
        if($tecnico === 'sin'){
            $w[] = "t.asignado_a IS NULL";
        } elseif(ctype_digit($tecnico)){
            $w[] = "t.asignado_a = ?";
            $types .= "i";
            $p[] = (int)$tecnico;
        }
    }

    return [implode(" AND ", $w), $types, $p];
}

function consulta($conexion, $sql, $types, $p){
    $st = $conexion->prepare($sql);
    if($types !== ""){
        $st->bind_param($types, ...$p);
    }
    $st->execute();
    return $st->get_result()->fetch_all(MYSQLI_ASSOC);
}

function e($v){
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function formatoDuracion($minutos){
    if($minutos === null){
        return "N/D";
    }
    $minutos = (int)round($minutos);
    $d = intdiv($minutos, 1440);
    $h = intdiv($minutos % 1440, 60);
    $m = $minutos % 60;
    $partes = [];
    if($d > 0) $partes[] = $d . "d";
    if($h > 0) $partes[] = $h . "h";
    if($m > 0 || count($partes) == 0) $partes[] = $m . "m";
    return implode(" ", $partes);
}
?>
