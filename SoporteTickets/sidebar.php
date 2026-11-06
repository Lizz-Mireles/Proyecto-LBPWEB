<?php
if(session_start()== PHP_SESSION_NONE){
   //session_start();
}
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
$rol = $_SESSION['usuario']['tipo'];
$nombre = $_SESSION['usuario']['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
    <title></title>
    <style>
        body{
            overflow-x:hidden;
        }
        .sidebar{
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: #212529;
            color: white;
            padding-top: 20px;
        }
        .sidebar h4{
            text-align:center;
            margin-bottom: 5px;
        }
        .sidebar p{
            text-align: center;
            color: #adb5bd;
            font-size: 14px;
        }
        .sidebar a{
            display: block;
            color: white;
            font-size: 14px;
            text-decoration: none;
            padding: 14px 20px;
            transition: .5s;
        }
        .sidebar a:hover{
            background: #0d6efd;
        }
.contenido{
    margin-left: 260px;
    padding: 20px;
    width: calc(100% - 260px);
}    
    </style>
</head> 
<body>
    <div class="sidebar">
        <h4>Sistema Tickets</h4>
        <p>
            <i class="bi bi-person-circle"></i>
            <?php echo $nombre; ?>
        </p>
        <hr class="text-secondary">
        <a href="dashboard.php">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>  
        <?php
        if($rol=="cliente"){ ?>
            <a href="nuevo_ticket.php">
                <i class="bi bi-plus-circle"></i>
                Nuevo Ticket
            </a>
            <a href="tickets_pendientes.php">
                <i class="bi bi-ticket-perforated"></i>
                Mis Tickets
            </a>
            <?php
        }
        ?>
        <?php
        if($rol=="tecnico"){?>
            <a href="tickets_pendientes.php">
                <i class="bi bi-tools"></i>
                Tickets Pendientes 
            </a>
            <a href="reportes.php">
                <i class="bi bi-graph-up"></i>
                Reportes
            </a>
        <?php
        }
        ?>
        <?php
        if($rol=="admin"){
            ?>
            <a href="nuevo_ticket.php">
                <i class="bi bi-plus-circle"></i>
                Nuevo Ticket
            </a>
            <a href="tickets_pendientes.php">
                <i class="bi bi-tools"></i>
                Tickets Pendientes
            </a>
            <a href="registro.php">
                <i class="bi bi-people"></i>
                Usuarios
            </a>
            <a href="reportes.php">
                <i class="bi bi-graph-up"></i>
                Reportes
            </a>
        <?php 
        }
        ?>
        <hr class="text-secundary">
        <a href="logout.php">
            <i class="bi bi-box-arrow-right"></i>
            Cerrar Sesion
        </a>
    </div>
</body>   
</html>
