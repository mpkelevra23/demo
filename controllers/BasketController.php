<?php

namespace Demo\controllers;

use Demo\components\Templater;
use Demo\models\User;

/**
 * Controller для работы с корзиной пользователя
 * Class BasketController
 */
class BasketController extends BaseController
{
    /**
     * Список товаров в корзине
     * @return bool|void
     */
    public function actionIndex()
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            //Титул страницы
            $title = 'Корзина';

            // Получаем id пользователя
            $userId = User::getUserId();

            // Информация о товарах
            $goods = self::getBasketObj()->getGoodsFromBasket($userId);

            // Общая стоймость товаров в корзине
            $totalPrice = self::getBasketObj()->getTotalPrice($userId);

            // Выводим
            echo Templater::viewInclude(
                './views/basket/index.php',
                [
                    'title' => $title,
                    'goods' => $goods,
                    'totalPrice' => $totalPrice
                ]
            );
            return true;
            // Подключаем вид
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }

    /**
     * Добавляем товар в корзину
     * @param $goodsId
     * @return bool|void
     */
    public function actionAdd($goodsId)
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Получаем id пользователя
            $userId = User::getUserId();

            // Если данного товара нет в корзине, то добавляем его
            if (!self::getBasketObj()->checkGoodsExistsInBasket($goodsId, $userId)) {
                // Получаем цену товара
                $goods = self::getGoodsObj()->getGoodById($goodsId);
                $price = $goods['price'];

                // Добавляем товар в корзину
                self::getBasketObj()->addGoodsInBasket($userId, $goodsId, $price);

                // Возвращаем пользователя на страницу
                $referrer = $_SERVER['HTTP_REFERER'];
                header("Location: $referrer");
            } else {
                self::showError('Данные товар уже добавлен в корзину');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }

    /**
     * Удаляем товар из корзины
     * @param $basketId
     */
    public function actionDelete($basketId)
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Получаем id пользователя
            $userId = User::getUserId();
            //Удаляем товар из корзины
            if (self::getBasketObj()->deleteFromBasket($userId, $basketId) == 1) {
                // Обновляем страницу
                header("Location: /basket/index");
            } else {
                self::showError('Ошибка удаления товара');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }
}
