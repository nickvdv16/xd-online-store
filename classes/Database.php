<?php

class Database
{
    private $host = "localhost";
    private $dbname = "online_store";
    private $username = "root";
    private $password = "";
    private $conn;

    public function connect()
    {
        if ($this->conn === null) {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $this->conn;
    }
}