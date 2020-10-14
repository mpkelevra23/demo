<?php

namespace Demo\models;

use Demo\components\Db;
use PDOStatement;

/**
 * Model для работы с товаром
 * Class Goods
 */
class Goods
{
    /**
     * Добавляем новый товар
     * @param $name
     * @param $price
     * @param $id_category
     * @param $imgAddress
     * @param $imgThumbAddress
     * @param bool $status
     * @param null $description
     * @return bool|false|PDOStatement
     */
    public function addNewGood(
        $name,
        $price,
        $id_category,
        $imgAddress,
        $imgThumbAddress,
        $status = true,
        $description = null
    ) {
        return Db::getInstance()->run(
            'INSERT INTO  demo.goods(name, price, id_category, img_address, img_thumb_address, status, description) VALUES (:name, :price, :id_category, :imgAddress, :imgThumbAddress, :status, :description)',
            [$name, $price, $id_category, $imgAddress, $imgThumbAddress, $status, $description]
        );
    }

    /**
     * Получаем массив товаров со статусом = 1
     * @return array
     */
    public function getGoodsList()
    {
        return Db::getInstance()->run(
            'SELECT id, name, price, img_thumb_address FROM demo.goods WHERE status = true'
        )->fetchAll();
    }

    /**
     * Получаем массив всех товаров
     * @return array
     */
    public function getAllGoodsList()
    {
        return Db::getInstance()->run('SELECT * FROM demo.goods_list')->fetchAll();
    }

    /**
     * Получаем товар по id
     * @param $goodId
     * @return mixed
     */
    public function getGoodById($goodId)
    {
        return Db::getInstance()->run('SELECT * FROM demo.goods WHERE id = :goodsId', [$goodId])->fetch();
    }

    /**
     * Получаем список категорий товаров
     * @return array
     */
    public function getCategoryList()
    {
        return Db::getInstance()->run('SELECT id, name FROM demo.categories')->fetchAll();
    }

    /**
     * Изменяем данные о товаре
     * @param $goodId
     * @param $name
     * @param $price
     * @param $categoryId
     * @param $status
     * @param $description
     * @param $imgAddress
     * @param $imgThumbAddress
     * @return mixed
     */
    public function updateGood(
        $goodId,
        $name,
        $price,
        $categoryId,
        $status,
        $description,
        $imgAddress,
        $imgThumbAddress
    ) {
        return Db::getInstance()->run(
            'UPDATE demo.goods SET name = :name, price = :price, id_category = :categoryId, status = :status, description = :description, img_address = :imgAddress, img_thumb_address = :imgThumbAddress WHERE id = :goodId',
            [$name, $price, $categoryId, $status, $description, $imgAddress, $imgThumbAddress, $goodId]
        )->rowCount();
    }

    /**
     * @param $goodsId
     * @return bool|false|PDOStatement
     */
    public function deleteGood($goodsId)
    {
        return Db::getInstance()->run('DELETE FROM demo.goods WHERE id = :goodsId', [$goodsId])->rowCount();
    }

    /**
     * Проверям цену
     * @param $price
     * @return bool
     */
    public function checkPrice($price)
    {
        if (is_float($price) && $price >= 0) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем существует ли товар с таким наименованием в бд
     * @param $name
     * @return bool
     */
    public function checkGoodExists($name)
    {
        if (Db::getInstance()->run('SELECT COUNT(*) FROM demo.goods WHERE name = :name', [$name])->fetchColumn()) {
            return true;
        }
        return false;
    }
}
