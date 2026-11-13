<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once("_db.php");
$id = $_GET['id'];
if($id== $_SESSION['usuario']['id']){
    if($id == $_SESSION['usuario']['id']){
        header("Location: registro.php?error=propio");
        exit();
    }
}
$sql= "DELETE FROM usuarios WHERE id=$id";
if($conexion->query($sql)){
    header("Location: registro.php");
    exit();
}else{
    echo "Error al eliminar el usuario: " . $conexion->error;
}
$conexion->close();
?>