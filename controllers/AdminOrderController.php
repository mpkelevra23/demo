<?php

namespace Demo\controllers;

use Demo\components\Templater;
use Demo\models\User;

/**
 * Controller для работы с заказами пользователей
 * Class AdminOrderController
 */
class AdminOrderController extends AdminBase
{
    /**
     * Список заказов
     * @return bool|void
     */
    public function actionIndex()
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Проверяем является ли пользователь администратором
            if (self::checkAdmin()) {
                // Список возможных статусов у заказа
                $orderStatus = self::getOrderObj()->getOrderStatusList();
                // Список заказов
                $orderList = self::getOrderObj()->getOrderList();
                //Титул страницы
                $title = 'Заказы';

                // Выводим
                echo Templater::viewInclude(
                    '../views/admin/order/index.php',
                    [
                        'title' => $title,
                        'orderStatus' => $orderStatus,
                        'orderList' => $orderList
                    ]
                );
                return true;
            } else {
                self::showError('Отказ в доступе');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }

    /**
     * Меняем статус заказа
     * @param $orderId
     * @return bool|void
     */
    public function actionUpdate($orderId)
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Проверяем является ли пользователь администратором
            if (self::checkAdmin()) {
                // Информация о заказе
                $order = self::getOrderObj()->getOrder($orderId);

                if (!empty($order)) {
                    // Меняем статус заказа
                    if (isset($_POST['submit']) && !empty($_POST['status'])) {
                        // id статуса
                        $statusId = $_POST['status'];

                        // Меняем статус заказа
                        if (self::getOrderObj()->updateOrderStatus($orderId, $statusId) == 1) {
                            // Обновляем страницу
                            header("Location: /admin/order/update/$orderId");
                        } else {
                            self::showError('Ошибка обновления статуса');
                        }
                    }
                    // Список возможных статусов у заказа
                    $orderStatus = self::getOrderObj()->getOrderStatusList();

                    //Титул страницы
                    $title = 'Заказ № ' . $orderId;

                    // Выводим
                    echo Templater::viewInclude(
                        '../views/admin/order/update.php',
                        [
                            'title' => $title,
                            'order' => $order,
                            'orderStatus' => $orderStatus
                        ]
                    );
                    return true;
                } else {
                    self::showError('Список заказов пуст');
                }
            } else {
                self::showError('Отказ в доступе');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }

    /**
     * Меняем статус заказа
     * @return bool|void
     */
    public function actionAjaxUpdate()
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Проверяем является ли пользователь администратором
            if (self::checkAdmin()) {
                // Список параметров для изменения статуса заказа
                $orderStatusParam = explode(', ', $_POST['orderStatusParams']);
                // Меняем статус заказа
                self::getOrderObj()->updateOrderStatus($orderStatusParam[0], $orderStatusParam[1]);
                return true;
            } else {
                self::showError('Отказ в доступе');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }

    /**
     * Удаляем заказ
     * @param $orderId
     */
    public function actionDelete($orderId)
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Проверяем является ли пользователь администратором
            if (self::checkAdmin()) {
                if (self::getOrderObj()->deleteOrder($orderId) == 1) {
                    // Обновляем страницу
                    header("Location: /admin/order/index");
                } else {
                    self::showError('Ошибка удаления заказа');
                }
            } else {
                self::showError('Отказ в доступе');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }
}
