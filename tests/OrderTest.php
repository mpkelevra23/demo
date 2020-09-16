<?php

// Подключаем автозагрузку классов
require_once '/var/www/demo.local/app/vendor/autoload.php';

use Demo\components\Db;
use Demo\models\Order;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    protected static $dbh;
    protected $fixture;

    public static function setUpBeforeClass(): void
    {
        self::$dbh = Db::getInstance();
        self::$dbh->run(
            "INSERT INTO demo.users(id, name, email, password) VALUES (1, 'test', 'test@test.com', 123456)"
        );
        self::$dbh->run(
            "INSERT INTO demo.goods(id, name, price, id_category, img_address, img_thumb_address, status, description) VALUES (1, 'test', 123456, 1, '/upload/img', '/upload/thumb', true, 'qwerty')"
        );
        self::$dbh->run("INSERT INTO demo.basket (id_user, id_good, price) VALUES (1, 1, 123456)");
    }

    public static function tearDownAfterClass(): void
    {
        self::$dbh->run("DELETE FROM demo.basket WHERE id_user = 1 AND id_good = 1");
        self::$dbh->run("DELETE FROM demo.goods WHERE id = 1");
        self::$dbh->run("DELETE FROM demo.users WHERE id = 1");
        self::$dbh = null;
    }

    /*
     * Создаю и удаляю записи в БД для работы тесто (ничего умнее не придумал)
     */

    public function setUp(): void
    {
        $this->fixture = new Order();
    }

    /**
     * @covers Order::addOrder
     * @return mixed
     */
    public function testAddOrder()
    {
        self::assertInstanceOf(PDOStatement::class, $this->fixture->addOrder(1, date("Y-m-d H:i:s"), 123456));
        return self::$dbh->lastInsertId('admin.demo.order_id_seq');
    }

    /**
     * @param $orderId
     * @covers  Order::getOrderInfo
     * @depends testAddOrder
     */
    public function testGetOrderInfo($orderId)
    {
        self::assertNotEmpty($this->fixture->getOrderInfo(1, $orderId));
        self::assertIsArray($this->fixture->getOrderInfo(1, $orderId));
    }

    /**
     * @param $userId
     * @covers  Order::getUserOrderList
     * @depends testAddOrder
     * @testWith [1]
     */
    public function testGetUserOrderList($userId)
    {
        self::assertIsArray($this->fixture->getUserOrderList($userId));
        self::assertNotEmpty($this->fixture->getUserOrderList($userId));
    }

    /**
     * @param $userId
     * @covers  Order::getLastUserOrders
     * @depends testAddOrder
     * @testWith [1]
     */
    public function testGetLastUserOrders($userId)
    {
        self::assertIsArray($this->fixture->getLastUserOrders($userId));
        self::assertNotEmpty($this->fixture->getLastUserOrders($userId));
    }

    /**
     * @param $orderId
     * @covers  Order::getOrder
     * @depends testAddOrder
     */
    public function testGetOrder($orderId)
    {
        self::assertIsArray($this->fixture->getOrder($orderId));
        self::assertNotEmpty($this->fixture->getOrder($orderId));
    }

    /**
     * @param $orderId
     * @param $statusId
     * @covers  Order::updateOrderStatus
     * @depends testAddOrder
     */
    public function testUpdateOrderStatus($orderId, $statusId = 3)
    {
        self::assertSame(1, $this->fixture->updateOrderStatus($orderId, $statusId));
    }

    /**
     * @covers  Order::getOrderList
     * @depends testAddOrder
     * @testWith [1]
     */
    public function testGetOrderList()
    {
        self::assertIsArray($this->fixture->getOrderList());
        self::assertNotEmpty($this->fixture->getOrderList());
    }

    /**
     * @covers  Order::getOrderStatusList
     * @depends testAddOrder
     */
    public function testGetOrderStatusList()
    {
        self::assertIsArray($this->fixture->getOrderStatusList());
        self::assertNotEmpty($this->fixture->getOrderStatusList());
    }

    /**
     * @param $orderId
     * @covers  Order::deleteOrder
     * @depends testAddOrder
     */
    public function testDeleteOrder($orderId)
    {
        self::assertSame(1, $this->fixture->deleteOrder($orderId));
    }

    protected function tearDown(): void
    {
        $this->fixture = null;
    }
}
