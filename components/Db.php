<?php

namespace Demo\components;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Class Db
 * Компонент для работы с базой данных
 */
final class Db
{
    // Переменная для хранения экземпляра класса
    private static $instance = null;

    // Переменная для хранения экземпляра PDO
    private $pdo;

    /**
     * Db constructor.
     * При создании объекта класса Db создаём объект класса PDO
     */
    private function __construct()
    {
        // Подключаем конфигурациооный файл
        $paramsPath = ROOT . '/config/db_params.php';
        $params = include_once($paramsPath);

        // Создаём объект класса PDO
        $dsn = "{$params['dbms']}:host={$params['host']};port={$params['port']};dbname={$params['dbname']}";

        try {
            $this->pdo = new PDO(
                $dsn, $params['user'], $params['password'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
            );
        } catch (PDOException $e) {
            print "<b>Ошибка соединения!:</b><br> " . $e->getMessage() . "<br>";
            die();
        }
    }

    /**
     * Метод проверки существования объекта класса Db, и для доступа к свойствам и методам класса Db
     * @return Db
     */
    public static function getInstance(): Db
    {
        return self::$instance ?? (self::$instance = new self());
    }

    /**
     * Закрываем соединение с БД
     */
    public static function closeDbh()
    {
        self::$instance = null;
    }

    /**
     * Вызов методов класса PDO
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->pdo, $method], $args);
    }

    /**
     * Выполнение запроса
     * @param $sql
     * @param array $args
     * @return bool|false|PDOStatement
     */
    public function run($sql, $args = [])
    {
        try {
            if (!$args) {
                return $this->pdo->query($sql);
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($args);
            return $stmt;
        } catch (PDOException $e) {
            print "<b>Ошибка запроса:</b><br> " . $e->getMessage() . "<br>";
            die();
        }
    }

    /**
     * Запрет на сериализацию объекта (для паттерна Singleton)
     */
    private function __wakeup()
    {
    }

    /**
     * Запрет на клонирование объекта (для паттерна Singleton)
     */
    private function __clone()
    {
    }
}
