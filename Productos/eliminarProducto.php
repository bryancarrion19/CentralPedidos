<?php
session_start();
require_once("Producto.php");
require_once("RepositorioProducto.php");
require_once("../conexion.php");

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $repo = new RepositorioProducto($conexion);

  $producto = $repo->findById($id);

  // Ahora eliminar el producto
  if ($producto) {
    if ($repo->delete($id)) {
      $_SESSION['message'] = "Producto eliminado exitosamente";
      $_SESSION['message_type'] = "success";
    } else {
      $_SESSION['message'] = "Error al eliminar el producto";
      $_SESSION['message_type'] = "danger";
    }
  }
}

header("Location: crudProductos.php");
exit();
