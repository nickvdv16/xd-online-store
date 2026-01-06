<?php

class Product
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            "mysql:host=localhost;dbname=online_store;charset=utf8",
            "root",
            "",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    public function getByCategory($categoryId, $limit = 6)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM products
            WHERE category_id = :category
            LIMIT :limit
        ");

        $stmt->bindValue(':category', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getById($id)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM products
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
}