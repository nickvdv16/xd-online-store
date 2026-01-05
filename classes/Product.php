<?php

require_once 'Database.php';

class Product
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getByCategory($categoryId, $limit = 6)
    {
        $sql = "SELECT * FROM products WHERE category_id = :category LIMIT :limit";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':category', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}