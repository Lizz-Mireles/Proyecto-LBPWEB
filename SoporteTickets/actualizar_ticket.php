<?php
session_start();
require_once("_db.php");
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}

$rol = $_SESSION['usuario']['tipo'];
$idUsuario = (int)$_SESSION['usuario']['id'];
$id = (int)$_POST['id'];
$estado = $_POST['estado'];

if(!in_array($estado, ['abierto','proceso','cerrado'])){
    exit("Estado inválido");
}

if($rol == "admin"){
    $asignado = (isset($_POST['asignado_a']) && $_POST['asignado_a'] !== "")
        ? (int)$_POST['asignado_a'] : "NULL";
    $sql = "UPDATE tickets SET estado='$estado', asignado_a=$asignado WHERE id=$id";
}elseif($rol == "tecnico"){
    $sql = "UPDATE tickets SET estado='$estado' WHERE id=$id AND asignado_a=$idUsuario";
}else{
    header("Location: dashboard.php");
    exit();
}

$conexion->query($sql);
header("Location: tickets_pendientes.php");
exit();
?>