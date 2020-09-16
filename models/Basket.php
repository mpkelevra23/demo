<?php

namespace Demo\models;

use Demo\components\Db;
use PDOStatement;

/**
 * Model для работы с корзиной пользователя
 * Class Basket
 */
class Basket
{
    /**
     * Добавляем товар в корзину пользователя
     * @param $userId
     * @param $goodsId
     * @param $price
     * @return bool|false|PDOStatement
     */
    public function addGoodsInBasket($userId, $goodsId, $price)
    {
        return Db::getInstance()->run(
            'INSERT INTO demo.basket (id_user, id_good, price) VALUES (:userId, :goodsId, :price)',
            [$userId, $goodsId, $price]
        );
    }

    /**
     * Получаем товары из корзины
     * @param $userId
     * @return array
     */
    public function getGoodsFromBasket($userId)
    {
        return Db::getInstance()->run(
            'SELECT basket.id AS basket_id, name, goods.price FROM demo.basket INNER JOIN demo.goods ON basket.id_good = goods.id WHERE id_user = :userId AND is_in_order = false',
            [$userId]
        )->fetchAll();
    }

    /**
     * Проверяем существует ли товар в корзине
     * @param $goodsId
     * @param $userId
     * @return mixed
     */
    public function checkGoodsExistsInBasket($goodsId, $userId)
    {
        return Db::getInstance()->run(
            'SELECT count(id) FROM demo.basket WHERE id_good = :goodsId AND id_user = :userId AND is_in_order = false',
            [$goodsId, $userId]
        )->fetchColumn();
    }

    /**
     * Получаем финальную стоимость товаров
     * @param $userId
     * @return mixed
     */
    public function getTotalPrice($userId)
    {
        return Db::getInstance()->run(
            'SELECT SUM(price) FROM demo.basket WHERE id_user = :userId AND is_in_order = false',
            [$userId]
        )->fetchColumn();
    }

    /**
     * Проверка на существование не заказанных товаров в корзине
     * @param $userId
     * @return mixed
     */
    public function checkBasketEmpty($userId)
    {
        return Db::getInstance()->run(
            'SELECT count(id) FROM demo.basket WHERE id_user = :userId AND is_in_order = false',
            [$userId]
        )->fetchColumn();
    }

    /**
     * Удаляем товар из корзины
     * @param $userId
     * @param $basketId
     * @return bool|false|PDOStatement
     */
    public function deleteFromBasket($userId, $basketId)
    {
        return Db::getInstance()->run(
            'DELETE FROM demo.basket WHERE id_user = :userId AND id = :basketId',
            [$userId, $basketId]
        )->rowCount();
    }
}
