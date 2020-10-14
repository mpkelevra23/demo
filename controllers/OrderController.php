<?php

namespace Demo\controllers;

use Demo\components\Templater;
use Demo\models\User;

/**
 * Controller для работы с заказами
 * Class OrderController
 */
class OrderController extends BaseController
{
    /**
     * Создаём заказ
     * @return bool|void
     */
    public function actionCreate()
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Получаем id пользователя
            $userId = User::getUserId();

            // Проверяем есть ли товар в корзине
            if (self::getBasketObj()->checkBasketEmpty($userId)) {
                //Титул страницы
                $title = 'Заказ оформлен';

                // Дата создания
                $createdAt = date("Y-m-d H:i:s");

                // Общая стоймость товаров в корзине
                $totalPrice = self::getBasketObj()->getTotalPrice($userId);

                // Создаём заказ
                self::getOrderObj()->addOrder($userId, $createdAt, $totalPrice);

                // Выводим
                echo Templater::viewInclude(
                    '../views/order/create.php',
                    ['title' => $title]
                );
                return true;
            } else {
                self::showError('Корзина пуста');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }

    /**
     * Выводим список заказов
     * @return bool|void
     */
    public function actionIndex()
    {
        if (!User::isGuest()) {
            // Получаем id пользователя
            $userId = User::getUserId();

            // Список заказов
            $orders = self::getOrderObj()->getUserOrderList($userId);

            //Титул страницы
            $title = 'Список заказов';

            // Выводим
            echo Templater::viewInclude(
                '../views/order/index.php',
                [
                    'title' => $title,
                    'orders' => $orders
                ]
            );
            return true;
        } else {
            self::showError('Надо войти в систему');
        }
    }

    /**
     * Просмотр заказа
     * @param $orderId
     * @return bool|void
     */
    public function actionView($orderId)
    {
        if (!User::isGuest()) {
            // Получаем id пользователя
            $userId = User::getUserId();

            // Получаем полную информацию о заказе
            $orders = self::getOrderObj()->getOrderInfo($userId, $orderId);

            if (!empty($orders)) {
                //Титул страницы
                $title = 'Заказ № ' . $orderId;

                // Дата и время заказа.
                $created = $orders[0]['created'];

                // Выводим
                echo Templater::viewInclude(
                    '../views/order/view.php',
                    [
                        'title' => $title,
                        'orderId' => $orderId,
                        'orders' => $orders,
                        'created' => $created
                    ]
                );
                return true;
            } else {
                self::showError('Такого заказа не существует');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }
}
