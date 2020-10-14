<?php

namespace Demo\controllers;

use Demo\components\Templater;
use Demo\models\Basket;
use Demo\models\Goods;
use Demo\models\Order;
use Demo\models\User;

/**
 * Абстрактный класс BaseController содержит общую логигу для контроллеров
 * Class BaseController
 */
abstract class BaseController
{
    // Объект класса User
    private $user;
    // Объект класса Basket
    private $basket;
    // Объект класса Order
    private $order;
    // Объект класса Goods
    private $goods;

    /**
     * Создаём объект класса User
     * Создаём объект класса Basket
     * Создаём объект класса Order
     * Создаём объект класса Goods
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->user = new User();
        $this->basket = new Basket();
        $this->order = new Order();
        $this->goods = new Goods();
    }

    /**
     * @return User
     */
    protected function getUserObj(): User
    {
        return $this->user;
    }

    /**
     * @return Basket
     */
    protected function getBasketObj(): Basket
    {
        return $this->basket;
    }

    /**
     * @return Order
     */
    protected function getOrderObj(): Order
    {
        return $this->order;
    }

    /**
     * @return Goods
     */
    protected function getGoodsObj(): Goods
    {
        return $this->goods;
    }

    /**
     * Подключаем вид с выводом ошибки
     * @param string $error
     * @return bool
     */
    protected function showError(string $error)
    {
        //Титул страницы
        $title = 'Ошибка';

        // Выводим
        echo Templater::viewInclude(
            '../views/site/error.php',
            [
                'title' => $title,
                'error' => $error
            ]
        );
        return true;
    }
}
