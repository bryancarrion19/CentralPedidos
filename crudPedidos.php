<?php
require_once("Pedido.php");
require_once("RepositorioPedidos.php");
require_once("conexion.php");

//Inicializar variables
$id = 0;
$iduser = $_SESSION['user_id'] ?? 0;
$idproducto = $fecha = $cantidad = 0;
$fecha = date('Y-m-d');

$repo = new RepositorioPedidos($conexion);

// Si viene un ID por GET y la acción es modificar
if (isset($_GET["accion"]) && $_GET["accion"] == "modificar" && isset($_GET["id"])) {
  $id = $_GET["id"];
  $pedido = $repo->findById($id);

  if ($pedido) {
    $iduser = $pedido->iduser;
    $idproducto = $pedido->idproducto;
    $fecha = $pedido->fecha;
    $cantidad = $pedido->cantidad;
  }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["id"])) {
  $pedido = new Pedido();

  $pedido->id = $_POST["id"];
  $pedido->iduser = $_POST["iduser"];
  $pedido->idproducto = $_POST["idproducto"];
  $pedido->fecha = $_POST["fecha"];
  $pedido->cantidad = $_POST["cantidad"];

  if ($repo->save($pedido)) {
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
    $repo = new RepositorioPedidos($conexion);
    $pedido = $repo->findById($id);

    $iduser = $pedido->iduser;
    $idproducto = $pedido->idproducto;
    $fecha = $pedido->fecha;
    $cantidad = $pedido->cantidad;
  }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRUD Pedidos</title>
  <?php require_once("cabecera.php"); ?>
</head>

<body class="bg-gray-100">
  <?php require_once("templates/navbarLoggedIn.php"); ?>

  <div class="container mx-auto px-4 py-8 mt-20">
    <div class="mb-6">
      <button type="button" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
        onclick="document.getElementById('pedidoModal').classList.remove('hidden')">
        Nuevo Pedido
      </button>
    </div>

    <!-- Modal -->
    <div id="pedidoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
      <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 shadow-lg rounded-md bg-white">
        <div class="flex flex-col">
          <div class="flex justify-between items-center border-b pb-3">
            <h3 class="text-xl font-semibold">Crear/Modificar Pedido</h3>
            <button type="button" class="text-gray-400 hover:text-gray-500"
              onclick="document.getElementById('pedidoModal').classList.add('hidden')">
              <span class="text-2xl">&times;</span>
            </button>
          </div>

          <form action="" method="post" class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">ID:</label>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <span class="text-gray-600"><?php echo $id ? $id : 'Nuevo Pedido'; ?></span>
              </div>
              <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">ID Usuario:</label>
                <input type="number" name="iduser" class="border rounded w-full py-2 px-3" value="<?php echo $iduser; ?>" readonly>
              </div>
              <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">ID Producto:</label>
                <input type="number" name="idproducto" class="border rounded w-full py-2 px-3" value="<?php echo $idproducto; ?>" required>
              </div>
              <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Fecha:</label>
                <input type="date" name="fecha" class="border rounded w-full py-2 px-3" value="<?php echo $fecha; ?>" required>
              </div>
              <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Cantidad:</label>
                <input type="number" name="cantidad" class="border rounded w-full py-2 px-3" value="<?php echo $cantidad; ?>" required>
              </div>
            </div>
            <div class="flex justify-end space-x-4 mt-4 pt-4 border-t">
              <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300"
                onclick="document.getElementById('pedidoModal').classList.add('hidden')">Cerrar</button>
              <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <h2 class="text-2xl font-bold mb-6">Gestionar Pedidos</h2>

    <!-- Tabla de pedidos -->
    <div class="bg-white rounded-lg shadow-md">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold">Lista de Pedidos</h3>
      </div>
      <div class="p-6">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Id</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuario</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Opciones</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <?php
              $lista = $repo->findAll();

              foreach ($lista as $pedido) {
                echo "<tr>";
                echo "<td class='px-6 py-4 whitespace-nowrap'>{$pedido->id}</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap'>{$pedido->iduser}</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap'>{$pedido->idproducto}</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap'>{$pedido->fecha}</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap'>{$pedido->cantidad}</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap space-x-2'>
                          <a href='?id={$pedido->id}&accion=modificar' 
                             class='inline-block bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600'>
                             Modificar
                          </a>
                          <a href='eliminarPedidos.php?id={$pedido->id}'
                             class='inline-block bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600' 
                             onclick='return confirm(\"¿Estás seguro de eliminar este pedido?\")'>
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
      document.getElementById('pedidoModal').classList.remove('hidden')
    }
  </script>
</body>

</html>