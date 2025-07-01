<?php
session_start();
require_once("../conexion.php");
require_once("RepositorioUsuario.php");

// Inicializar variables para evitar warnings
$mensaje = "";
$tipo_mensaje = "";

// Verificar si el usuario está logueado
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['accion'])) {
            $accion = $_POST['accion'] ?? null;
            $saldo = $_POST['saldo'] ?? 0;
            $id = $_SESSION["user_id"];

            $repositorio = new RepositorioUsuario($conexion);
            if ($accion === 'recargar_saldo') {
                if ($repositorio->recargarSaldo($id, $saldo)) {
                    $mensaje = "Fondos recargados con éxito. Se añadieron €{$saldo} a tu saldo.";
                    $tipo_mensaje = "success";
                } else {
                    throw new Exception("Error al recargar fondos.");
                }
            }
        } else {
            if (isset($_FILES["imagen"])) {

                $imagen = date("Y-m-d-H-i-s") . "-" . $_FILES['imagen']['name'];
                $file_loc = $_FILES['imagen']['tmp_name'];
                $upload_dir = "../img/";

                if (move_uploaded_file($file_loc, $upload_dir . $imagen)) {
                    $id = $_SESSION["user_id"];

                    // Obtener avatar anterior
                    $sql = "SELECT avatar FROM users WHERE id = :id";
                    $consulta = $conexion->prepare($sql);
                    $consulta->bindParam(":id", $id);
                    $consulta->execute();
                    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                    $avatar_anterior = $resultado['avatar'];

                    // Actualizar nuevo avatar
                    $sql = "UPDATE users SET avatar=:imagen WHERE id=:id";
                    $consulta = $conexion->prepare($sql);
                    $consulta->bindParam(":imagen", $imagen);
                    $consulta->bindParam(":id", $id);
                    $consulta->execute();
                } else {
                    throw new Exception("Error al mover el archivo subido.");
                }
            } else {
                throw new Exception("Por favor, selecciona una imagen.");
            }
        }
    } catch (Exception $e) {
        $mensaje = "Error: " . $e->getMessage();
        $tipo_mensaje = "error";
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de usuario</title>
    <?php require_once("../cabecera.php"); ?>
</head>

<body>
    <?php require_once("../templates/navbarLoggedIn.php"); ?>

    <div class="max-w-4xl mx-auto px-4 py-12 mt-16">
        <?php if ($mensaje): ?>
            <div class="mb-4 p-4 rounded-lg <?php echo $tipo_mensaje == 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-transparent hover:border-gray-500 transition-all duration-300 ease-in-out">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Tu perfil</h1>

            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <div class="relative">
                        <?php
                        $id = $_SESSION["user_id"];
                        $sql = "SELECT avatar, saldo FROM users WHERE id = :id";
                        $consulta = $conexion->prepare($sql);
                        $consulta->bindParam(":id", $id);
                        $consulta->execute();
                        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
                        $avatar = $resultado['avatar'] ?? 'default-avatar.png';
                        $saldo = $resultado['saldo'];
                        ?>
                        <img src="../img/<?php echo $avatar; ?>"
                            alt="Avatar actual"
                            class="w-32 h-32 rounded-full object-cover border-4 border-indigo-100 shadow-md">
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Avatar actual</h2>
                        <p class="text-gray-600 text-sm mb-2">JPG, PNG o GIF permitidos. Tamaño máximo 2MB.</p>
                    </div>
                </div>

                <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
                    <div class="flex flex-col space-y-2">
                        <label for="imagen" class="block text-sm font-medium text-gray-700">
                            Seleccionar nueva imagen
                        </label>
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                <input type="file"
                                    name="imagen"
                                    id="imagen"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4
                                              file:rounded-full file:border-0 file:text-sm file:font-semibold
                                              file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100
                                              cursor-pointer border rounded-lg p-2">
                            </div>
                            <button type="submit"
                                class="inline-flex justify-center py-2.5 px-6 border border-transparent 
                                           shadow-sm text-sm font-medium rounded-lg text-white 
                                           bg-indigo-600 hover:bg-indigo-700 focus:outline-none 
                                           focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                                           transition-colors duration-200">
                                Actualizar avatar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-8 bg-white rounded-2xl shadow-lg p-8 border-2 border-transparent hover:border-gray-500 transition-all duration-300 ease-in-out">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Tu Cartera Virtual</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Saldo Actual -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl p-6 text-white">
                    <h3 class="text-lg font-semibold mb-2">Saldo Disponible</h3>
                    <div class="text-3xl font-bold">€<?php echo $saldo; ?></div>
                    <div class="mt-4 text-sm opacity-80">Última actualización: <?php echo date('d/m/Y H:i'); ?></div>
                </div>

                <!-- Acciones Rápidas -->

                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
                    <div class="space-y-3">
                        <form action="" method="POST">
                            <input type="number" name="saldo" placeholder="Cantidad" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm placeholder-gray-400 transition duration-200 ease-in-out" />
                            <button type="submit" name="accion" value="recargar_saldo"
                                class="w-full bg-green-500 mt-8 hover:bg-green-600 text-white py-2 px-4 rounded-lg transition-colors duration-200">
                                Agregar Fondos
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

</body>

</html>