<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "SoporteTickets";


$conexion = mysqli_connect(
    hostname: $host,
    username: $user,
    password: $password,
    database: $database,
);

if (!$conexion){
    echo "No se realizo la conexion a la base de datos, <br>";
    echo "Error: " . mysqli_connect_error();
}else{
    echo "Conexion exitosa";
}
?>
