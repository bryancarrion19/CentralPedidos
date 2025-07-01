<?php
require_once("Usuario.php");

class RepositorioUsuario
{
    public $conexion;

    public function __construct($conexion2)
    {
        $this->conexion = $conexion2;
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM users WHERE id=:id";
        $consulta = $this->conexion->prepare($sql);
        $consulta->setFetchMode(PDO::FETCH_OBJ);
        $consulta->bindParam(":id", $id, PDO::PARAM_INT);
        $consulta->execute();

        if ($fila = $consulta->fetch()) {
            $usuario = new Usuario();
            $usuario->setProperties($fila->id, $fila->username, $fila->email, $fila->avatar, $fila->saldo);
        } else {
            $usuario = null;
        }

        return $usuario;
    }

    public function findAll()
    {
        $sql = "SELECT * FROM users";
        $consulta = $this->conexion->prepare($sql);
        $consulta->setFetchMode(PDO::FETCH_OBJ);
        $consulta->execute();

        $listaUsuarios = [];
        while ($fila = $consulta->fetch()) {
            $usuario = new Usuario();
            $usuario->setProperties($fila->id, $fila->username, $fila->email, $fila->avatar, $fila->saldo);
            $listaUsuarios[] = $usuario;
        }
        return $listaUsuarios;
    }

    public function save($u)
    {
        if ($u->id == null || $u->id == 0) {
            $sql = "INSERT INTO users (username, email, avatar) VALUES (?, ?, ?)";
            $consulta = $this->conexion->prepare($sql);
            $consulta->execute([$u->username, $u->email, $u->avatar]);
            $u->id = $this->conexion->lastInsertId();
        } else {
            if ($this->findById($u->id)) {
                $sql = "UPDATE users SET username=?, email=?, avatar=? WHERE id=?";
                $consulta = $this->conexion->prepare($sql);
                $consulta->execute([$u->username, $u->email, $u->avatar, $u->id]);
            }
        }

        return $u;
    }

    public function deleteById($id)
    {
        $sql = "DELETE FROM users WHERE id=?";
        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([$id]);
    }

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email=:email";
        $consulta = $this->conexion->prepare($sql);
        $consulta->setFetchMode(PDO::FETCH_OBJ);
        $consulta->bindParam(":email", $email, PDO::PARAM_STR);
        $consulta->execute();

        if ($fila = $consulta->fetch()) {
            $usuario = new Usuario();
            $usuario->setProperties($fila->id, $fila->username, $fila->email, $fila->avatar, $fila->saldo);
        } else {
            $usuario = null;
        }

        return $usuario;
    }

    public function recargarSaldo($id, $saldo)
    {
        try {
            $sql = "UPDATE users SET saldo = saldo + :saldo WHERE id = :id";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(":id", $id);
            $consulta->bindParam(":saldo", $saldo);
            return $consulta->execute();
        } catch (PDOException $e) {
            error_log("Error al recargar saldo: " . $e->getMessage());
            return false;
        }
    }

    public function descontarSaldo($id, $saldo)
    {
        try {
            $sql = "UPDATE users SET saldo = saldo - :saldo WHERE id = :id";
            $consulta = $this->conexion->prepare($sql);
            $consulta->bindParam(":id", $id);
            $consulta->bindParam(":saldo", $saldo);
            return $consulta->execute();
        } catch (PDOException $e) {
            error_log("Error al descontar saldo: " . $e->getMessage());
            return false;
        }
    }
}
