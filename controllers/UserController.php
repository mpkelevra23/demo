<?php

namespace Demo\controllers;

use Demo\components\Templater;
use Demo\models\User;

/**
 * Controller для работы с пользователем
 * Class UserController
 */
class UserController extends BaseController
{
    /**
     * Регистрация пользователя
     * @return bool
     */
    public function actionRegistration()
    {
        // Переменные для формы
        $errors = false;
        $name = false;
        $email = false;
        $password = false;

        // Если пользователь не вошёл в систему, отправляем его на главную страницу
        if (!User::isGuest()) {
            header("Location: /");
        } elseif (isset($_POST['submit'])) {
            $name = (string)htmlspecialchars(strip_tags($_POST['name']));
            $email = (string)htmlspecialchars(strip_tags($_POST['email']));
            $password = (string)htmlspecialchars(strip_tags($_POST['password']));

            // Проверяем полученный данные от пользователя
            if (!User::checkName($name)) {
                $errors['name'] = 'Имя должно быть не меньше 6-ти символов';
            }
            if (!User::checkPassword($password)) {
                $errors['password'] = 'Пароль должно быть не меньше 6-ти символов';
            }
            if (!User::checkEmail($email)) {
                $errors['email'] = 'Неправильно указан адрес электронной почты';
            }
            if (self::getUserObj()->checkEmailExists($email)) {
                $errors['email'] = 'Такой email уже существует';
            }

            if (empty($errors)) {
                // Если данные правильные, регистрируем пользователя на сайте, и возвращаем его id из бд
                $userId = self::getUserObj()->registration($name, $email, $password);
                // Запоминаем пользователя (в сессию)
                self::getUserObj()->authorization($userId);
                // Перенаправляем пользователя в закрытую часть - кабинет
                header("Location: /cabinet/");
            }
        }

        //Титул страницы
        $title = 'Регистрация';

        // Выводим
        echo Templater::viewInclude(
            './views/user/registration.php',
            [
                'title' => $title,
                'errors' => $errors,
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]
        );
        return true;
    }

    /**
     * Аутентификация и авторизация пользователя
     * @return bool
     */
    public function actionLogin()
    {
        // Переменные для формы
        $errors = false;
        $email = false;
        $password = false;

        // Если пользователь не вошёл в систему, отправляем его на главную страницу
        if (!User::isGuest()) {
            header("Location: /");
        } elseif (isset($_POST['submit'])) {
            $email = (string)htmlspecialchars(strip_tags($_POST['email']));
            $password = (string)htmlspecialchars(strip_tags($_POST['password']));

            // Проверяем полученный данные от пользователя
            if (!User::checkEmail($email)) {
                $errors['email'] = 'Неправильный email';
            } elseif (!self::getUserObj()->checkEmailExists($email)) {
                $errors['email'] = 'Пользователя с данным email не существует в базе';
            }
            if (!User::checkPassword($password)) {
                $errors['password'] = 'Пароль не должен быть короче 6-ти символов';
            }

            // Если нет ошибок
            if (empty($errors)) {
                // Если данные правильные, аутентифицируем пользователя (существует ли данный пользоваель в бд)
                $userId = self::getUserObj()->authentication($email, $password);
                //Проверяем есть ли такой пользователь
                if ($userId == false) {
                    // Если данные неправильные - показываем ошибку
                    $errors['userId'] = 'Неправильные данные для входа на сайт';
                } else {
                    // Если данные правильные, авторизируем (запоминаем пользователя в сессию)
                    self::getUserObj()->authorization($userId);

                    // Перенаправляем пользователя в закрытую часть - кабинет
                    header("Location: /cabinet/");
                }
            }
        }

        //Титул страницы
        $title = 'Вход';

        // Выводим
        echo Templater::viewInclude(
            './views/user/login.php',
            [
                'title' => $title,
                'errors' => $errors,
                'email' => $email,
                'password' => $password
            ]
        );
        return true;
    }

    /**
     * Удаляем данные о пользователе из сессии
     */
    public function actionLogout()
    {
        // Если пользователь не вошёл в систему, отправляем его на главную страницу
        if (User::isGuest()) {
            header("Location: /");
        } else {
            //Сохраняем 5 последних адресов, на которых был пользователь
            $userId = $_SESSION['user'];
            $last_actions = array_slice($_SESSION['last_actions'], -5, 5);
            self::getUserObj()->saveLastActions($userId, $last_actions);

            // Удаляем данные пользователя из сессии
            $_SESSION['user'] = null;
            $_SESSION['last_actions'] = null;
            session_destroy();

            // Переходим на главную страницу
            header('Location: /');
        }
    }
}
