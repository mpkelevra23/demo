<?php

// FRONT CONTROLLER

declare(strict_types=1);

use Demo\components\Db;
use Demo\components\Router;
use Demo\models\User;

// Общие настройки
// Включаем отображение ошибок
ini_set('display_errors', '1');
error_reporting(E_ALL);

// Подключаем автозагрузку классов composer
require_once '../vendor/autoload.php';

// Стартуем сессию
session_start();

// Отслеживаем действия пользователя
if (!User::isGuest()) {
    User::trackingUserActions();
}

// Вызов Router
Router::run();

// Закрываем соединение с БД
Db::closeDbh();
