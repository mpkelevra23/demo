<?php

namespace Demo\models;

use Demo\components\Db;
use Demo\components\Router;
use PDOStatement;

/**
 * Model для работы с пользователем
 * Class User
 */
class User
{
    /**
     * Проверяем является ли пользователь гостем
     * @return bool
     */
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }

    /**
     * Проверяем email
     * @param $email
     * @return bool
     */
    public static function checkEmail($email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем имя
     * @param $name
     * @return bool
     */
    public static function checkName($name)
    {
        if (strlen($name) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем пароль
     * @param $password
     * @return bool
     */
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Отслеживаем последние адреса, на которых был пользователь
     * @return bool|string
     */
    public static function trackingUserActions()
    {
        // Проверям существует ли массив с последними посещёнными страницами
        if (isset($_SESSION['last_actions']) && is_array($_SESSION['last_actions'])) {
            // Считываем кол-во элементов в массиве
            $count = count($_SESSION['last_actions']);

            // Увеличиваем на единицу
            $count++;

            // Добавляем новый элемент в массив
            return $_SESSION['last_actions'][$count] = Router::getURI();
        }
        return false;
    }

    /**
     * Регистрация пользователя в бд
     * @param $name
     * @param $email
     * @param $password
     * @return mixed
     */
    public function registration($name, $email, $password)
    {
        // Хэшируем пароль
        $password = password_hash($password, PASSWORD_BCRYPT);

        // Выполняем запрос
        Db::getInstance()->run(
            'INSERT INTO demo.users(name, email, password) VALUES (:name, :email, :password)',
            [$name, $email, $password]
        );

        // Получаем id нового пользователя
        return Db::getInstance()->lastInsertId('admin.demo.users_id_seq');
    }

    /**
     * Аутентификация пользователя
     * @param $email
     * @param $password
     * @return bool
     */
    public function authentication($email, $password)
    {
        // Выполняем запрос
        $user = Db::getInstance()->run('SELECT id, password FROM demo.users WHERE email = :email', [$email])->fetch();

        // Если пароль верный, возвращаем id пользователя
        if (password_verify($password, $user['password'])) {
            return $user['id'];
        }
        return false;
    }

    /**
     * Авторизация пользователя
     * @param $userId
     * @return array|bool|mixed
     */
    public function authorization($userId)
    {
        // Записываем id пользователя в сессию
        $_SESSION['user'] = $userId;
        // Записываем данные о последних посещённых страницах
        if (self::getLastActions($_SESSION['user']) == false) {
            // Присваиваем пустой массив, если нет данных о последних посещённых страницах
            return $_SESSION['last_actions'] = [];
        } else {
            // Присваиваем данные о последних посещённых страницах
            return $_SESSION['last_actions'] = self::getLastActions($userId);
        }
    }

    /**
     * Получаем 5 последних адресов, на которых был пользователь
     * @param $id
     * @return bool|mixed
     */
    public function getLastActions($id)
    {
        // Выполняем запрос
        $result = Db::getInstance()->run('SELECT last_actions FROM demo.users WHERE id = :id', [$id])->fetch();

        // Возвращаем результат запроса
        if (!is_null($result)) {
            return unserialize($result['last_actions']);
        } else {
            return false;
        }
    }

    /**
     * Получаем данные о пользователе
     * @param $id
     * @return mixed
     */
    public function getUserById($id)
    {
        // Выполняем и возвращаем результат запрос
        return Db::getInstance()->run('SELECT * FROM demo.users WHERE id = :id', [$id])->fetch();
    }

    /**
     * Проверяем является ли пользователь админом (2 = admin)
     * @return bool
     */
    public function isAdmin()
    {
        if (Db::getInstance()->run(
            'SELECT id FROM demo.user_role WHERE id_user = :id_user AND id_role = 2',
            [self::getUserId()]
        )->fetch()) {
            return true;
        }
        return false;
    }

    /**
     * Возвращает идентификатор пользователя, если он авторизирован.
     * @return bool|mixed
     */
    public static function getUserId()
    {
        // Если сессия есть, вернем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        return false;
    }

    /**
     * Проверяем существует ли email в бд
     * @param $email
     * @return bool
     */
    public function checkEmailExists($email)
    {
        if (Db::getInstance()->run('SELECT COUNT(*) FROM demo.users WHERE email = :email', [$email])->fetchColumn()) {
            return true;
        }
        return false;
    }

    /**
     * Сохраняем 5 последних адресов, на которых был пользователь
     * @param $userId
     * @param $last_actions
     * @return bool|false|PDOStatement
     */
    public function saveLastActions($userId, $last_actions)
    {
        // Сериализуем последние действия пользователя
        $last_actions = serialize($last_actions);

        // Выполняем запрос
        return Db::getInstance()->run(
            'UPDATE demo.users SET last_actions = :last_actions WHERE id = :userId',
            [$last_actions, $userId]
        )->rowCount();
    }

    /**
     * @param $userId
     * @return bool|false|PDOStatement
     */
    public function deleteUser($userId)
    {
        return Db::getInstance()->run('DELETE FROM demo.users WHERE id = :id', [$userId])->rowCount();
    }
}
