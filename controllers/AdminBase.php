<?php

namespace Demo\controllers;

use Demo\models\User;

/**
 * Абстрактный класс AdminBase содержит общую логику для контроллеров, которые используются в панели администратора
 */
abstract class AdminBase extends BaseController
{
    /**
     * Статический метод, который проверяет пользователя на то, является ли он администратором
     * @return bool
     */
    public static function checkAdmin()
    {
        // Объект класса User
        $user = new User();

        return $user->isAdmin();
    }
}
