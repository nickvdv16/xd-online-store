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

    /* =========================
       PRODUCTEN PER CATEGORIE
       (met optionele limit)
    ========================= */
    public function getByCategory(int $categoryId, ?int $limit = null)
    {
        $sql = "
            SELECT *
            FROM products
            WHERE category_id = :category
        ";

        if ($limit !== null) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':category', $categoryId, PDO::PARAM_INT);

        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /* =========================
       PRODUCTEN PER CATEGORIE
       MET SORTERING
    ========================= */
    public function getByCategorySorted(int $categoryId, string $column, string $direction)
    {
        $allowedColumns = ['price', 'title'];
        $allowedDirections = ['ASC', 'DESC'];

        if (!in_array($column, $allowedColumns) || !in_array($direction, $allowedDirections)) {
            return [];
        }

        $stmt = $this->pdo->prepare("
            SELECT *
            FROM products
            WHERE category_id = :category
            ORDER BY $column $direction
        ");

        $stmt->execute([
            'category' => $categoryId
        ]);

        return $stmt->fetchAll();
    }

    /* =========================
       PRODUCT OP ID
    ========================= */
    public function getById(int $id)
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