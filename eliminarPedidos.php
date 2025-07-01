<?php
session_start();
require_once("Pedido.php");
require_once("RepositorioPedidos.php");
require_once("conexion.php");

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $repo = new RepositorioPedidos($conexion);

  $pedido = $repo->findById($id);

  if ($pedido) {
    if ($repo->delete($id)) {
      $_SESSION['message'] = "Pedido eliminado correctamente";
      $_SESSION['message_type'] = "success";
    } else {
      $_SESSION['message'] = "Error al eliminar el pedido";
      $_SESSION['message_type'] = "danger";
    }
  }
}

header("Location: crudPedidos.php");
exit();
