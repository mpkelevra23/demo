<?php

// Подключаем автозагрузку классов
require_once '/var/www/demo.local/app/vendor/autoload.php';

use Demo\components\Db;
use Demo\models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected static $dbh;
    protected $fixture;

    public static function setUpBeforeClass(): void
    {
        self::$dbh = Db::getInstance();
        self::$dbh->run(
            "INSERT INTO demo.users(id, name, email, password, last_actions) VALUES (1, 'test', 'test@test.com', 123456, 'a:5:{i:0;s:7:\"cabinet\";i:1;s:10:\"order/list\";i:2;s:13:\"order/view/56\";i:3;s:0:\"\";i:4;s:11:\"user/logout\";}')"
        );
    }

    public static function tearDownAfterClass(): void
    {
        self::$dbh->run("DELETE FROM demo.users WHERE id = 1");
        self::$dbh = null;
    }

    /*
     * Создаю и удаляю записи в БД для работы тесто (ничего умнее не придумал)
     */

    public function setUp(): void
    {
        $this->fixture = new User();
    }

    /**
     * @covers User::isGuest
     */
    public function testIsGuest()
    {
        self::assertIsBool(User::isGuest());
    }

    /**
     * @covers User::trackingUserActions
     */
    public function testTrackingUserActions()
    {
        self::assertIsBool(User::trackingUserActions());
    }

    /**
     * @covers User::checkPassword
     */
    public function testCheckPassword()
    {
        self::assertTrue(User::checkPassword('qwerty'));
        self::assertFalse(User::checkPassword('test'));
    }

    /**
     * @param $userId
     * @covers User::getLastActions
     * @testWith [1]
     */
    public function testGetLastActions($userId)
    {
        self::assertIsArray($this->fixture->getLastActions($userId));
    }

    /**
     * @covers User::getUserId
     */
    public function testGetUserId()
    {
        self::assertFalse(User::getUserId());
    }

    /**
     * @param $userId
     * @covers User::authorization
     * @testWith [1]
     */
    public function testAuthorization($userId)
    {
        self::assertIsArray($this->fixture->authorization($userId));
        self::assertNotEmpty($this->fixture->authorization($userId));
    }

    /**
     * @return mixed
     * @covers User::registration
     */
    public function testRegistration()
    {
        self::assertNotEmpty($this->fixture->registration('qwerty', 'qwerty@qwerty.com', 123456));
        return self::$dbh->lastInsertId('admin.demo.users_id_seq');
    }

    /**
     * @covers  User::isAdmin
     * @depends testRegistration
     */
    public function testIsAdmin()
    {
        self::assertFalse($this->fixture->isAdmin());
    }

    /**
     * @param $userId
     * @covers  User::authentication
     * @depends testRegistration
     */
    public function testAuthentication($userId)
    {
        self::assertEquals($userId, $this->fixture->authentication('qwerty@qwerty.com', 123456));
    }

    /**
     * @covers  User::checkEmailExists
     * @depends testRegistration
     */
    public function testCheckEmailExists()
    {
        self::assertTrue($this->fixture->checkEmailExists('qwerty@qwerty.com'));
    }

    /**
     * @covers  User::checkEmail
     * @depends testRegistration
     */
    public function testCheckEmail()
    {
        self::assertTrue(User::checkEmail('qwerty@qwerty.com'));
    }

    /**
     * @param $userId
     * @covers  User::saveLastActions
     * @depends testRegistration
     */
    public function testSaveLastActions($userId)
    {
        self::assertSame(
            1,
            $this->fixture->saveLastActions(
                $userId,
                'a:5:{i:0;s:6:"basket";i:1;s:0:"";i:2;s:6:"basket";i:3;s:6:"basket";i:4;s:11:"user/logout";}'
            )
        );
    }

    /**
     * @param $userId
     * @covers  User::getUserById
     * @depends testRegistration
     */
    public function testGetUserById($userId)
    {
        self::assertIsArray($this->fixture->getUserById($userId));
        self::assertNotEmpty($this->fixture->getUserById($userId));
    }

    /**
     * @covers User::checkName
     */
    public function testCheckName()
    {
        self::assertTrue(User::checkName('qwerty'));
        self::assertFalse(User::checkName('test'));
    }

    /**
     * @param $userId
     * @covers  User::deleteUser
     * @depends testRegistration
     */
    public function testDeleteUser($userId)
    {
        self::assertSame(1, $this->fixture->deleteUser($userId));
    }

    protected function tearDown(): void
    {
        $this->fixture = null;
    }
}
