<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["username"])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Por favor ingresa usuario y contraseña";
    } else {
        $stmt = $conexion->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['logged_in'] = true;

            header("Location: /central2/index.php");
            exit();
        } else {
            $error = "Usuario o contraseña incorrectos";
            $_SESSION['login_error'] = $error;
            header("Location: /central2/index.php");
            exit();
        }
    }
}
