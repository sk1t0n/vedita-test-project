<?php

declare(strict_types=1);

namespace App\Database;

$parse = parse_ini_file(__DIR__ . '/../configuration/config.ini', true);
$host = $parse['dsn']['host'];
$port = $parse['dsn']['port'];
$dbName = $parse['dsn']['db_name'];
$dsn = "mysql:host=$host;port=$port;dbname=$dbName";
$user = $parse['db_user'];
$password = $parse['db_password'];
$dbOptions = $parse['db_options'];

/**
 * Класс для работы с таблицей "Products" в базе данных MySQL.
 *
 * @author Антон Грабовский <agrabovsky2020@gmail.com>
 */
class CProducts
{
    private $pdo = null;

    public function __construct(string $dsn, string $user, string $password, array $dbOptions)
    {
        $this->connectDB($dsn, $user, $password, $dbOptions);
    }

    /**
     * Подключение к БД.
     *
     * @param string $dsn Специальная строка для подключения к БД. Содержит хост, порт, имя базы данных.
     * @param string $user Имя пользователя БД.
     * @param string $password Пароль пользователя БД.
     * @param array $dbOptions Настройки БД.
     * @return void
     */
    private function connectDB(string $dsn, string $user, string $password, array $dbOptions): void
    {
        try {
            $this->pdo = new \PDO($dsn, $user, $password, $dbOptions);
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Запрашивает в базе данных товары, соответствующие параметрам limit и offset.
     *
     * @param integer $limit Количество товара, которое нужно получить.
     * @param integer $offset Смещение курсора в БД.
     * @return array Возвращает массив с товарами.
     */
    public function getProducts(int $limit, int $offset): array
    {
        try {
            $products = [];
            $query = 'SELECT * FROM Products WHERE HIDDEN = 0 '
        . "ORDER BY DATE_CREATE DESC LIMIT $limit OFFSET $offset;";
            $rows = $this->pdo->query($query);
            foreach ($rows as $row) {
                $products[] = $row;
            }
        } catch (\Exception $e) {
            return [];
        }

        return $products;
    }

    /**
     * Запрашивает количество нескрытых товаров в БД.
     *
     * @return integer Возвращает количество нескрытых товаров.
     */
    public function getTotalProducts(): int
    {
        $query = 'SELECT COUNT(*) AS total FROM Products WHERE HIDDEN = 0';
        $row = $this->pdo->query($query)->fetch();

        return (int) $row['total'];
    }

    /**
     * Делает запрос в БД на скрытие товара.
     *
     * @param integer $id Идентификатор товара, который нужно скрыть.
     * @return void
     */
    public function hideProduct(int $id): void
    {
        $query = 'UPDATE  Products SET HIDDEN = 1 WHERE ID = ?;';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$id]);
    }
}

$db = new CProducts($dsn, $user, $password, $dbOptions);
