<?php
// Configuración de conexión
if ($_SERVER['SERVER_NAME'] === "localhost") {
    $host = 'localhost';
    $port = '3306';
    $dbname = 'central';
    $username = 'root';
    $password = '';
} else {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $dbname = getenv('DB_NAME') ?: '';
    $username = getenv('DB_USER') ?: '';
    $password = getenv('DB_PASS') ?: '';
}

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $conexion = new PDO($dsn, $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Crear tabla de usuarios
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    confirmado BOOLEAN,
    clave_confirmacion VARCHAR(255),
    avatar VARCHAR(255),
    saldo INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    $conexion->exec($sql);
} catch (PDOException $e) {
    die("Error creando tabla users: " . $e->getMessage());
}

// Crear tabla de productos
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
try {
    $conexion->exec($sql);
} catch (PDOException $e) {
    die("Error creando tabla productos: " . $e->getMessage());
}

// Crear tabla de pedidos
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
)";
try {
    $conexion->exec($sql);
    echo "Tablas creadas correctamente.";
} catch (PDOException $e) {
    die("Error creando tabla pedidos: " . $e->getMessage());
}
