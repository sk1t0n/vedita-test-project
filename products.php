<?php

declare(strict_types=1);

$parse = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini', true);
$host = $parse['dsn']['host'];
$port = $parse['dsn']['port'];
$dbName = $parse['dsn']['db_name'];
$dsn = "mysql:host=$host;port=$port;dbname=$dbName";
$user = $parse['db_user'];
$password = $parse['db_password'];
$dbOptions = $parse['db_options'];


class CProducts
{
    private $pdo = null;

    public function __construct(string $dsn, string $user, string $password, array $dbOptions)
    {
        $this->connectDB($dsn, $user, $password, $dbOptions);
    }

    private function connectDB(string $dsn, string $user, string $password, array $dbOptions): void
    {
        try {
            $this->pdo = new PDO($dsn, $user, $password, $dbOptions);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getProducts(int $limit, int $offset): array
    {
        try {
            $products = [];
            $query = "SELECT * FROM Products WHERE HIDDEN = 0 "
        . "ORDER BY DATE_CREATE DESC LIMIT $limit OFFSET $offset;";
            $rows = $this->pdo->query($query);
            foreach ($rows as $row) {
                $products[] = $row;
            }
        } catch (Exception $e) {
            return [];
        }

        return $products;
    }

    public function getTotalProducts(): int
    {
        $query = "SELECT COUNT(*) AS total FROM Products WHERE HIDDEN = 0";
        $row = $this->pdo->query($query)->fetch();

        return (int) $row['total'];
    }

    public function hideProduct(int $id): void
    {
        $query = "UPDATE  Products SET HIDDEN = 1 WHERE ID = ?;";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
    }
}

class Pagination
{
    public static function getPageCount(int $total, int $itemsPerPage): int
    {
        try {
            $pageCount = (int) ceil($total / $itemsPerPage);
            return $pageCount;
        } catch (Exception $e) {
            return 1;
        }
    }
}

$db = new CProducts($dsn, $user, $password, $dbOptions);
