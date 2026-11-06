<?php
session_start();
require_once('_db.php');
$username = $_POST['username'];
$password = $_POST['password'];
$sql = "SELECT * FROM usuarios WHERE username='$username'";
$result=$conexion->query($sql );
if($result->num_rows >0){
    $row = $result->fetch_assoc();
    $hashedPassword = $row['password'];
    if(password_verify(password: $password, hash: $hashedPassword)){
        $tipoUsusario = $row['rol'];
        $nombreusuario = $row['nombre'];
        $_SESSION['usuario'] = [
            'id' => $row['id'],
            'tipo' => $tipoUsusario,
            'nombre' => $nombreusuario
        ];
        if($tipoUsusario==="cliente"){
            header("Location: dashboard.php");
        }else{
            if($tipoUsusario==="tecnico"){
                header("Location: dashboard.php");
            }else{
                if($tipoUsusario==="admin"){
                    header("Location: dashboard.php");
                }
            }
        }
        exit();
    }
}
$conexion->close();
header("Location: index.php?error=1");
exit();
?>
 
