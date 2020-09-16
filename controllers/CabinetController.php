<?php

namespace Demo\controllers;

use Demo\components\Templater;
use Demo\models\User;

/**
 * Controller для работы с личным кабинетом пользователя
 * Class CabinetController
 */
class CabinetController extends BaseController
{
    /**
     * Вход в личный кабинет
     * @return bool|void
     */
    public function actionIndex()
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Получаем id пользователя
            $userId = User::getUserId();

            // Получаем данные о пользователе из бд
            $user = self::getUserObj()->getUserById($userId);

            // Получаем 5 последних заказов пользователя, сортируем по дате заказа
            $lastOrders = self::getOrderObj()->getLastUserOrders($userId);

            // Получаем 5 последних адресов, на которых был пользователь
            if (isset($_SESSION['last_actions']) && is_array($_SESSION['last_actions'])) {
                //Титул страницы
                $title = 'Личный кабинет';

                // Последние действия пользователя
                $lastActions = array_slice($_SESSION['last_actions'], -5, 5);

                // Выводим
                echo Templater::viewInclude(
                    './views/cabinet/index.php',
                    [
                        'title' => $title,
                        'user' => $user,
                        'lastActions' => $lastActions,
                        'lastOrders' => $lastOrders
                    ]
                );
                return true;
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }
}
