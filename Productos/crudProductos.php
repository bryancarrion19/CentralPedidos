<?php
require_once("Producto.php");
require_once("RepositorioProducto.php");
require_once("../conexion.php");

$id = 0;
$nombre = "";
$tipo = "";
$tamanio = "";
$precio = 0;
$descripcion = "";
$imagen = "";

$repo = new RepositorioProducto($conexion);

if (isset($_GET["accion"]) && $_GET["accion"] == "modificar" && isset($_GET["id"])) {
    $id = $_GET["id"];
    $producto = $repo->findById($id);

    if ($producto) {
        $nombre = $producto->nombre;
        $tipo = $producto->tipo;
        $tamanio = $producto->tamanio;
        $precio = $producto->precio;
        $descripcion = $producto->descripcion;
        $imagen = $producto->imagen;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["id"])) {
    $producto = new Producto();

    $producto->id = $_POST["id"];
    $producto->nombre = $_POST["nombre"];
    $producto->descripcion = $_POST["descripcion"];
    $producto->tamanio = $_POST["tamanio"];
    $producto->tipo = $_POST["tipo"];
    $producto->precio = $_POST["precio"];

    $producto->imagen = "";

    if (isset($_FILES["imagen"]) && $_FILES["imagen"]["name"] != "") {
        $imagen = date("Y-m-d-H-i-s") . "-" . $_FILES['imagen']['name'];
        $file_loc = $_FILES['imagen']['tmp_name'];
        if (!is_uploaded_file($file_loc)) {
            echo "El archivo no existe en el directorio temporal.";
        }
        if ($_FILES["imagen"]["error"] !== UPLOAD_ERR_OK) {
            echo "Error al subir archivo: " . $_FILES["imagen"]["error"];
        }
        move_uploaded_file($file_loc, "../img/" . $imagen);
        $producto->imagen = $imagen;
        if (file_exists("../img/" . $imagen)) {
            echo "Archivo movido exitosamente.";
        } else {
            echo "El archivo no se movió correctamente.";
        }
    }
    if ($repo->save($producto)) {
        $_SESSION['message'] = "Pedido guardado exitosamente";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error al guardar el pedido";
        $_SESSION['message_type'] = "danger";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
} else {
    if (isset($_GET["accion"]) && $_GET["accion"] == "modificar") {
        $id = $_GET["id"];
        $repo = new RepositorioProducto($conexion);
        $producto = $repo->findById($id);

        $nombre = $producto->nombre;
        $tipo = $producto->tipo;
        $tamanio = $producto->tamanio;
        $precio = $producto->precio;
        $descripcion = $producto->descripcion;
        $imagen = $producto->imagen;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Productos</title>
    <?php require_once("../cabecera.php"); ?>
</head>

<body class="bg-gray-100">
    <?php
    require_once("../templates/navbarLoggedIn.php");
    ?>
    <div class="container mx-auto px-4 py-8 mt-20">
        <div class="mb-6">
            <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors" onclick="document.getElementById('productoModal').classList.remove('hidden')">
                Nuevo Producto
            </button>
        </div>

        <!-- Modal -->
        <div id="productoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
                <div class="flex flex-col">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-xl font-semibold">Crear/Modificar Producto</h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="document.getElementById('productoModal').classList.add('hidden')">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>

                    <form action="" method="post" enctype="multipart/form-data" class="mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">ID:</label>
                                <input type="text" name="id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $id; ?>" readonly>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                                <input type="text" name="nombre" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $nombre; ?>" required>
                            </div>
                            <div class="mb-4 col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Descripción</label>
                                <input type="text" name="descripcion" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $descripcion; ?>">
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tamaño</label>
                                <select name="tamanio" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Seleccione un tamaño</option>
                                    <option value="Pequeño" <?php echo ($tamanio == 'Pequeño') ? 'selected' : ''; ?>>Pequeño</option>
                                    <option value="Mediano" <?php echo ($tamanio == 'Mediano') ? 'selected' : ''; ?>>Mediano</option>
                                    <option value="Grande" <?php echo ($tamanio == 'Grande') ? 'selected' : ''; ?>>Grande</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Precio</label>
                                <input type="number" step=".1" name="precio" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?php echo $precio; ?>" required>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tipo</label>
                                <div class="space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="tipo" value="Comida" <?php echo ($tipo == 'Comida') ? 'checked' : ''; ?> required class="form-radio text-blue-600">
                                        <span class="ml-2">Comida</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="tipo" value="Bebida" <?php echo ($tipo == 'Bebida') ? 'checked' : ''; ?> class="form-radio text-blue-600">
                                        <span class="ml-2">Bebida</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="tipo" value="Otros" <?php echo ($tipo == 'Otros') ? 'checked' : ''; ?> class="form-radio text-blue-600">
                                        <span class="ml-2">Otros</span>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-4 col-span-2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Imagen</label>
                                <input type="file" name="imagen" class="w-full">
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4 mt-4 pt-4 border-t">
                            <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300" onclick="document.getElementById('productoModal').classList.add('hidden')">Cerrar</button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <h2 class="text-2xl font-bold mb-6">Gestionar Productos</h2>

        <!-- Tabla de productos -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Lista de Productos</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Id</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamaño</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $repo = new RepositorioProducto($conexion);
                            $lista = $repo->findAll();

                            foreach ($lista as $producto) {
                                echo "<tr>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>{$producto->id}</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>{$producto->nombre}</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>{$producto->precio}</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'><img src='../img/{$producto->imagen}' class='h-12 w-12 object-cover rounded-md'></td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>{$producto->tamanio}</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap'>{$producto->tipo}</td>";
                                echo "<td class='px-6 py-4 whitespace-nowrap space-x-2'>
                                        <a href='?id={$producto->id}&accion=modificar' onclick='document.getElementById(\"productoModal\").classList.remove(\"hidden\")' class='inline-block bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600'>
                                            Modificar
                                        </a>
                                        <a href='eliminarProducto.php?id={$producto->id}' class='inline-block bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600' onclick='return confirm(\"¿Estás seguro de eliminar este producto?\")'>
                                            Eliminar
                                        </a>
                                      </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.GET = location.search.substr(1).split("&").reduce((o, i) => (u = decodeURIComponent, [k, v] = i.split("="), o[u(k)] = v && u(v), o), {});

        if (window?.GET['accion'] == 'modificar') {
            document.getElementById('productoModal').classList.remove('hidden')
        }
    </script>
</body>

</html>