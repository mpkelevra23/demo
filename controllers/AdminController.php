<?php

namespace Demo\controllers;

use Demo\components\Templater;
use Demo\models\User;

/**
 * Controller для работы с админкой
 * Class AdminController
 */
class AdminController extends AdminBase
{
    /**
     * Action для стартовой страницы "Панель администратора"
     * @return bool|void
     */
    public function actionIndex()
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Проверяем является ли пользователь администратором
            if (self::checkAdmin()) {
                //Титул страницы
                $title = 'Админка';

                // Выводим
                echo Templater::viewInclude(
                    './views/admin/index.php',
                    ['title' => $title]
                );
                return true;
            } else {
                self::showError('Отказ в доступе');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }
}
