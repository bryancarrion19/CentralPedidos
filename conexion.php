<?php
if ($_SERVER['SERVER_NAME'] == "localhost") {
    $host = 'localhost';
    $dbname = 'central';
    $username = 'root';
    $password = '';
} else {
    $host = getenv('DB_HOST') ?: 'localhost';
    $dbname = getenv('DB_NAME') ?: '';
    $username = getenv('DB_USER') ?: '';
    $password = getenv('DB_PASS') ?: '';
}

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    confirmado BOOLEAN,
    clave_confirmacion varchar(255),
    avatar varchar(255),
    saldo INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    $conexion->exec($sql);
} catch (PDOException $e) {
    die("Error creando tabla: " . $e->getMessage());
}

$sql = "CREATE TABLE IF NOT EXISTS productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    descripcion TEXT,
    tamanio VARCHAR(50),
    tipo VARCHAR(50),
    imagen VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conexion->exec($sql);

$sql = "CREATE TABLE IF NOT EXISTS pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha DATE,
    cantidad INT,
    precio DECIMAL(10,2) NOT NULL,
    iduser INT NOT NULL,
    idproducto INT NOT NULL,
    FOREIGN KEY (iduser) REFERENCES users(id),
    FOREIGN KEY (idproducto) REFERENCES productos(id)
);";
$conexion->exec($sql);
