<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Login</title>
    <style>
        body{
            background-image: url('./img/bg.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center; 
        }
        .login-container{
            background-color: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
        }
        h2{
            color: white;
            font-size: 50px;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        @media (max-width: 768px) {
            h2 {
                font-size: 36px;
            }
        }
        .login-container {
            padding: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h2>Login</h2>
        <div class="login-container mx-auto">
            <h4 class="mb-4">Inicio de sesión</h4>
            
            <form action="validar_usuario.php" method="post">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Ingrese su usuario" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese su contraseña" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Iniciar sesión</button>  
                </div>
            </form>

            <?php if(isset($_GET['error'])){ ?>
                <div class="alert alert-danger py-2 px-3 mt-3 mb-0 small">Usuario o contraseña incorrectos</div>
            <?php } ?>
            <hr>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.history.replaceState({}, document.title, window.location.pathname);
        
        setTimeout(function(){
            var alerta = document.querySelector('.alert, .text-danger');
            if(alerta){ alerta.remove(); }
        }, 4000);
    </script>
</body>
</html>
