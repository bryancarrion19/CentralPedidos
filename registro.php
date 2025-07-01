<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["username"])) {
    echo 2;
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Todos los campos son obligatorios";
    } elseif ($password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } else {
        echo 3;
        try {
            $stmt = $conexion->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
        } catch (PDOException $e) {
            $error = "Error en el registro. Por favor intenta nuevamente.";
            echo $error;
        }
        echo 4;
        if ($stmt->rowCount() > 0) {
            $error = "El usuario o email ya está registrado";
        } else {
            echo 5;
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            echo 6;

            try {
                $stmt->execute([$username, $email, $hashed_password]);
                $_SESSION['success'] = "Registro exitoso. Por favor inicia sesión.";
                header("Location: /central2/index.php");
                exit();
            } catch (PDOException $e) {
                $error = "Error en el registro. Por favor intenta nuevamente." . $e->getMessage();
                echo $error;
            }
        }
    }
}
