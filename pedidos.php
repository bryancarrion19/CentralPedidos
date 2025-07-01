<?php
session_start();
require_once("conexion.php");
require_once("Productos/Producto.php");
require_once("Productos/RepositorioProducto.php");
require_once("usuarios/Usuario.php");
require_once("usuarios/RepositorioUsuario.php");
require_once("Pedido.php");
require_once("RepositorioPedidos.php");

$repo = new RepositorioProducto($conexion);

$lista = $repo->findAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["boton"])) {
    $repoPedidos = new RepositorioPedidos($conexion);

    foreach ($lista as $producto) {
        $nombre = "cb" . $producto->id;
        if (isset($_POST[$nombre]) && $_POST[$nombre]) {
            $p = new Pedido();
            $p->iduser = $_SESSION['user_id'];
            $p->idproducto = $producto->id;
            $p->cantidad = 1;
            $p->precio = $producto->precio;
            $p->fecha = date("y-m-d");

            $repoPedidos->save($p);
        }
    }
    $telefono = "641050051";
    $mensaje = urlencode("Pedido realizado con éxito");
    $url = "https://web.whatsapp.com/send/?phone={$telefono}&text={$mensaje}";
    header("Location: $url");
    exit;
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realizar Pedido - Central</title>
    <?php require_once("cabecera.php"); ?>
</head>

<body class="bg-gray-50">
    <?php require_once("templates/navbarLoggedIn.php"); ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 mt-24">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Realizar nuevo pedido</h1>

        <form action="" method="POST">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php
                foreach ($lista as $producto) {
                    echo "<div class='bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden'>";
                    $nombre = "cb" . $producto->id;
                    echo "<div class='relative group'>";
                    echo "<img src='img/{$producto->imagen}' class='w-full h-48 object-cover' alt='{$producto->nombre}'>";
                    echo "<div class='absolute top-3 right-3'>";
                    echo "<div class='bg-white/80 backdrop-blur-sm p-1 rounded-lg group-hover:bg-white transition-colors duration-300'>";
                    echo "<input type='checkbox' name='$nombre' class='w-6 h-6 rounded-md border-2 border-indigo-600 text-indigo-600 
                          focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer transition-all duration-300 
                          checked:bg-indigo-600 checked:border-indigo-600'>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='p-4'>";
                    echo "<h3 class='font-semibold text-gray-800'>{$producto->nombre}</h3>";
                    echo "<p class='text-indigo-600 font-bold mt-2'>{$producto->precio}€</p>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>

            <div class='text-center mt-10'>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all duration-300" type='submit' name='boton'>
                    Realizar Pedido
                </button>
            </div>
        </form>

        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Historial de Pedidos</h2>
            <div class="bg-white shadow-lg rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Producto</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Usuario</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Imagen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php
                            $repoPedidos = new RepositorioPedidos($conexion);
                            $repoProductos = new RepositorioProducto($conexion);
                            $repoUsuarios = new RepositorioUsuario($conexion);
                            $lista = $repoPedidos->findAll();

                            foreach ($lista as $pedido) {
                                $producto = $repoProductos->findById($pedido->idproducto);
                                $usuario = $repoUsuarios->findById($pedido->iduser);
                                echo "<tr class='hover:bg-gray-50 transition-colors duration-200'>";
                                echo "<td class='px-6 py-4 text-sm text-gray-600'>{$pedido->id}</td>";
                                echo "<td class='px-6 py-4 text-sm font-medium text-gray-800'>{$producto->nombre}</td>";
                                echo "<td class='px-6 py-4 text-sm text-gray-600'>{$usuario->username}</td>";
                                echo "<td class='px-6 py-4'><img src='img/{$producto->imagen}' class='h-12 w-12 rounded-lg object-cover shadow-sm' alt='Producto'></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>