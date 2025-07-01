<?php
class Usuario
{
    public $id;
    public $username;
    public $email;
    public $avatar;
    public $saldo;

    public function __construct() {}

    public function setProperties($id, $username, $email, $avatar, $saldo)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->avatar = $avatar;
        $this->saldo = $saldo;
    }

    public function toString()
    {
        return "{$this->id} - {$this->username} - {$this->email} - {$this->saldo}";
    }
}
