<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once("_db.php");
$id= $_POST['id'];
$nombre= $_POST['nombre'];
$username= $_POST['username'];
$password= $_POST['password'];
$rol= $_POST['rol'];
$hora_salida= $_POST['hora_salida'];
$activo= $_POST['activo'];
if(!empty($password)){
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql= "UPDATE usuarios set
    nombre= '$nombre',
    username= '$username',
    password= '$hash',
    rol= '$rol',
    hora_salida= '$hora_salida',
    activo= '$activo'
    WHERE id= $id";
}else{
    $sql= "UPDATE usuarios SET
    nombre= '$nombre',
    username= '$username',
    rol= '$rol',
    hora_salida= '$hora_salida',
    activo= '$activo'
    WHERE id= $id";
}
if($conexion->query($sql)){
    header("Location: registro.php");
    exit();
}else{
    echo
    "Error al actualizar: " . $conexion->error;
}
$conexion->close();
?>