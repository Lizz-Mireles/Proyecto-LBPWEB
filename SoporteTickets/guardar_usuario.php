<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once("_db.php");
$nombre = $_POST['nombre'];
$username = $_POST['username'];
$password = $_POST['password'];
$rol = $_POST['rol'];
$hora_salida = $_POST['hora_salida'];
$activo = $_POST['activo'];
 
$hash = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO usuarios
(nombre, username, password, rol, hora_salida, activo)
VALUES
('$nombre', '$username', '$hash', '$rol', '$hora_salida', '$activo')";
if($conexion->query($sql)){
    header("Location: registro.php");
    exit();
}else{
    echo "Error al crear el usuario: " . $conexion->error;
}
 
$conexion->close();
?>