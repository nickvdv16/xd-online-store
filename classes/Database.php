<?php

class Database {
    private $host = "localhost";
    private $db   = "online_store";
    private $user = "root";
    private $pass = "";

    public function connect() {
        $pdo = new PDO(
            "mysql:host={$this->host};dbname={$this->db};charset=utf8",
            $this->user,
            $this->pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        return $pdo;
    }
}