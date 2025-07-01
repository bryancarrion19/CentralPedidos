<?php
require_once("conexion.php");
require_once("Pedido.php");
require_once("RepositorioPedidos.php");
require_once("Productos/RepositorioProducto.php");

$repo = new RepositorioPedidos($conexion);
$lista = [];
$lista = $repo->findByFecha(date("y-m-d"));
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos de hoy</title>
    <?php require_once("cabecera.php"); ?>
</head>

<body class="bg-gray-100">
    <?php require_once("templates/navbarLoggedIn.php"); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-24">
        <?php
        echo "<h1 class='text-4xl font-bold text-gray-900 mb-6'>Pedidos del día <span class='text-indigo-600'>" . date('d-m-y') . "</span></h1>";

        echo "<div class='bg-white shadow-xl rounded-xl overflow-hidden border border-gray-200'>";
        echo "<div class='grid grid-cols-12 gap-4 p-6 bg-gray-50 border-b border-gray-200 font-semibold text-gray-700'>";
        echo "<div class='col-span-2'>Cliente</div>";
        echo "<div class='col-span-2'>Producto</div>";
        echo "<div class='col-span-4'>Descripción</div>";
        echo "<div class='col-span-2'>Tamaño</div>";
        echo "<div class='col-span-2'>Precio</div>";
        echo "</div>";

        foreach ($lista as $pedido) {
            $idusuario = $pedido->iduser;
            $sql = "SELECT * FROM users WHERE id=:id";
            $consulta = $conexion->prepare($sql);
            $consulta->bindParam(":id", $idusuario);
            $consulta->setFetchMode(PDO::FETCH_OBJ);
            $consulta->execute();
            $fila = $consulta->fetch();
            $imagen = $fila->avatar;
            $repoProductos = new RepositorioProducto($conexion);
            $producto = $repoProductos->findById($pedido->idproducto);

            echo "<div class='grid grid-cols-12 gap-4 p-6 border-b border-gray-200 items-center transition duration-150 ease-in-out hover:bg-gray-50'>";
            echo "<div class='col-span-2'><img src='img/$imagen' class='h-12 w-12 rounded-full object-cover border-2 border-gray-200'></div>";
            echo "<div class='col-span-2'><img src='img/{$producto->imagen}' class='h-12 w-12 rounded-lg object-cover shadow-sm'></div>";
            echo "<div class='col-span-4 text-gray-800 font-medium'>{$producto->nombre}</div>";
            echo "<div class='col-span-2 text-gray-600'>{$producto->tamanio}</div>";
            echo "<div class='col-span-2 font-semibold text-indigo-600'>{$producto->precio}€</div>";
            echo "</div>";
        }
        echo "</div>";
        ?>
    </div>
</body>

</html>