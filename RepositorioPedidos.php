<?php
require_once("Pedido.php");

class RepositorioPedidos
{
    public $conexion;

    public function __construct($conexion2)
    {
        $this->conexion = $conexion2;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM pedidos WHERE id=:id";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(":id", $id);
        $consulta->setFetchMode(PDO::FETCH_CLASS, "Pedido");
        $consulta->execute();
        return $consulta->fetch();
    }

    public function findAll()
    {
        $sql = "SELECT * FROM pedidos";
        $consulta = $this->conexion->prepare($sql);
        $consulta->setFetchMode(PDO::FETCH_OBJ);
        $consulta->execute();

        $listaPedidos = [];
        while ($fila = $consulta->fetch()) {
            $pedido = new Pedido();
            $pedido->setProperties(
                $fila->id,
                $fila->iduser,
                $fila->idproducto,
                $fila->fecha,
                $fila->cantidad,
                $fila->precio
            );
            $listaPedidos[] = $pedido;
        }
        return $listaPedidos;
    }

    public function save($p)
    {
        if ($p->id == null || $p->id == 0) {
            $sql = "INSERT INTO pedidos (iduser, idproducto, fecha, cantidad, precio) VALUES (?, ?, ?, ?, ?)";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([$p->iduser, $p->idproducto, $p->fecha, $p->cantidad, $p->precio]);
            $p->id = $this->conexion->lastInsertId();
        } else {
            //Compruebo si el id existe
            if ($this->findById($p->id)) {
                $sql = "UPDATE pedidos SET iduser=?, idproducto=?, fecha=?, cantidad=?, precio=? WHERE id=? ";
                $consulta = $this->conexion->prepare($sql);
                $consulta->execute([$p->iduser, $p->idproducto, $p->fecha, $p->cantidad, $p->precio, $p->id]);
            }
        }

        return $p;
    }

    public function setEstado($p)
    {
        $sql = "UPDATE pedidos SET estado=? WHERE id=?";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([$p->estado, $p->id]);

        return $p;
    }

    public function findByFecha($fecha)
    {
        $sql = "SELECT * FROM pedidos WHERE fecha=:fecha";
        $consulta = $this->conexion->prepare($sql);
        $consulta->setFetchMode(PDO::FETCH_OBJ);
        $consulta->bindParam(":fecha", $fecha);
        $consulta->execute();

        $listaPedidos = [];
        while ($fila = $consulta->fetch()) {
            $pedido = new Pedido();
            $pedido->setProperties(
                $fila->id,
                $fila->iduser,
                $fila->idproducto,
                $fila->fecha,
                $fila->cantidad,
                $fila->precio
            );
            $listaPedidos[] = $pedido;
        }

        return $listaPedidos;
    }

    public function update(Pedido $pedido)
    {
        $sql = "UPDATE pedidos SET iduser=:iduser, idproducto=:idproducto, fecha=:fecha, cantidad=:cantidad, precio=:precio WHERE id=:id";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindValue(":id", $pedido->id);
        $consulta->bindValue(":iduser", $pedido->iduser);
        $consulta->bindValue(":idproducto", $pedido->idproducto);
        $consulta->bindValue(":fecha", $pedido->fecha);
        $consulta->bindValue(":cantidad", $pedido->cantidad);
        $consulta->bindValue(":precio", $pedido->precio);
        return $consulta->execute();
    }

    public function getUltimosPedidos($limit = 5)
    {
        $sql = "SELECT p.*, pr.nombre as nombre_producto 
                FROM pedidos p 
                JOIN productos pr ON p.idproducto = pr.id 
                ORDER BY p.fecha DESC 
                LIMIT :limit";
        $consulta = $this->conexion->prepare($sql);
        $consulta->bindParam(":limit", $limit, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProductoFavorito()
    {
        $sql = "SELECT p.idproducto, pr.nombre, COUNT(*) as total_pedidos, SUM(p.cantidad) as cantidad_total
                FROM pedidos p
                JOIN productos pr ON p.idproducto = pr.id
                GROUP BY p.idproducto, pr.nombre
                ORDER BY cantidad_total DESC
                LIMIT 1";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute();
        return $consulta->fetch(PDO::FETCH_OBJ);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM pedidos WHERE id = :id";
        $consulta = $this->conexion->prepare($sql);
        return $consulta->execute([':id' => $id]);
    }
}
