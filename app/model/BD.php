<?php

namespace classes;
class BD
{
    private $server;
    private $user;
    private $pass;
    private $bd;

    private function __construct($server, $user, $pass, $bd)
    {
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
        $this->bd = $bd;
    }

    private function conectar()
    {
        $conn = new PDO("pgsql:host=" . $this->server . ";dbname=" . $this->bd, $this->user, $this->pass);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }

    public static function FloresNuria()
    {
        $bd = new BD("localhost", "postgres", "1234", "flores_nuria");
        return $bd->conectar();

    }
}