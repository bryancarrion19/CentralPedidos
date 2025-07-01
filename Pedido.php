<?php
require_once("Productos/Producto.php");
require_once("Productos/RepositorioProducto.php");
require_once("usuarios/Usuario.php");
require_once("usuarios/RepositorioUsuario.php");

class Pedido
{
    public $id;
    public $iduser;
    public $idproducto;
    public $fecha;
    public $cantidad;
    public $precio;

    public function __construct() {}

    public function setProperties($id, $iduser, $idproducto, $fecha, $cantidad, $precio)
    {
        $this->id = $id;
        $this->iduser = $iduser;
        $this->idproducto = $idproducto;
        $this->fecha = $fecha;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
    }

    public function getUser($conexion)
    {
        $repoUsuarios = new RepositorioUsuario($conexion);
        return $repoUsuarios->findById($this->iduser);
    }
}
