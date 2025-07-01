<?php
    session_start();
    require_once("comprueba.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php require_once("templates/navbarLoggedIn.php"); ?>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title">Dashboard</h2>
                <p class="card-text">¡Bienvenido a tu panel de control!</p>
                <hr>
                <p>Este es tu espacio personal. Aquí podrás gestionar tu cuenta y acceder a todas las funcionalidades.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
