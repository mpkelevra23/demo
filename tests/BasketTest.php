<?php

// Подключаем автозагрузку классов
require_once './vendor/autoload.php';

use Demo\components\Db;
use Demo\models\Basket;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
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
    }

    public static function tearDownAfterClass(): void
    {
        self::$dbh->run("DELETE FROM demo.goods WHERE id = 1");
        self::$dbh->run("DELETE FROM demo.users WHERE id = 1");
        self::$dbh = null;
    }

    /*
     * Создаю и удаляю записи в БД для работы тесто (ничего умнее не придумал)
     */

    public function setUp(): void
    {
        $this->fixture = new Basket();
    }

    /**
     * @covers Basket::addGoodsInBasket
     * @return mixed
     */
    public function testAddGoodsInBasket()
    {
        self::assertInstanceOf(PDOStatement::class, $this->fixture->addGoodsInBasket(1, 1, 123456));
        return self::$dbh->lastInsertId('admin.demo.basket_id_seq');
    }

    /**
     * @param $userId
     * @depends      testAddGoodsInBasket
     * @covers       Basket::getGoodsFromBasket
     * @testWith [1]
     */
    public function testGetGoodsFromBasket($userId)
    {
        self::assertIsArray($this->fixture->getGoodsFromBasket($userId));
        self::assertNotEmpty($this->fixture->getGoodsFromBasket($userId));
    }

    /**
     * @param $userId
     * @depends testAddGoodsInBasket
     * @covers  Basket::getTotalPrice
     * @testWith [1]
     */
    public function testGetTotalPrice($userId)
    {
        self::assertNotEmpty($this->fixture->getTotalPrice($userId));
    }

    /**
     * @param $goodId
     * @param $userId
     * @depends testAddGoodsInBasket
     * @covers  Basket::checkGoodsExistsInBasket
     * @testWith [1, 1]
     */
    public function testCheckGoodsExistsInBasket($goodId, $userId)
    {
        self::assertIsInt($this->fixture->checkGoodsExistsInBasket($goodId, $userId));
        self::assertNotEmpty($this->fixture->checkGoodsExistsInBasket($goodId, $userId));
    }

    /**
     * @param $userId
     * @depends testAddGoodsInBasket
     * @covers  Basket::checkBasketEmpty
     * @testWith [1]
     */
    public function testCheckBasketEmpty($userId)
    {
        self::assertIsInt($this->fixture->checkBasketEmpty($userId));
        self::assertNotEmpty($this->fixture->checkBasketEmpty($userId));
    }

    /**
     * @param $basketId
     * @covers  Basket::deleteFromBasket
     * @depends testAddGoodsInBasket
     */
    public function testDeleteFromBasket($basketId)
    {
        self::assertSame(1, $this->fixture->deleteFromBasket(1, $basketId));
    }

    protected function tearDown(): void
    {
        $this->fixture = null;
    }
}
