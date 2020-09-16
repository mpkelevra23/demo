<?php

namespace Demo\controllers;

use Demo\components\Picture;
use Demo\components\Templater;
use Demo\models\User;

/**
 * Controller для работы с товарами пользователей
 * Class AdminGoodsController
 */
class AdminGoodsController extends AdminBase
{
    /**
     * Список товаров
     * @return bool|void
     */
    public function actionIndex()
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Проверяем является ли пользователь администратором
            if (self::checkAdmin()) {
                // Список заказов
                $goodsList = self::getGoodsObj()->getAllGoodsList();

                //Титул страницы
                $title = 'Товары';

                // Выводим
                echo Templater::viewInclude(
                    './views/admin/goods/index.php',
                    [
                        'title' => $title,
                        'goodsList' => $goodsList
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
     * Создаём новый товар
     * @return bool|void
     */
    public function actionCreate()
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Проверяем является ли пользователь администратором
            if (self::checkAdmin()) {
                // Список категорий товаров
                $categoryList = self::getGoodsObj()->getCategoryList();

                //Титул страницы
                $title = 'Создать новый товар';

                if (isset($_POST['submit'])) {
                    // Переменные для формы
                    $name = (string)htmlspecialchars(strip_tags($_POST['name']));
                    $price = (float)htmlspecialchars(strip_tags($_POST['price']));
                    $categoryId = (int)htmlspecialchars(strip_tags($_POST['categoryId']));
                    $status = (int)htmlspecialchars(strip_tags($_POST['status']));

                    // Проверяем полученный данные от пользователя
                    if (self::getGoodsObj()->checkGoodExists($name)) {
                        $errors['name'] = 'Товар с таким наименованием уже существует';
                    }
                    if (!self::getGoodsObj()->checkPrice($price)) {
                        $errors['price'] = 'Цена должна быть больше 0';
                    }
                    if (isset($_POST['description'])) {
                        $description = (string)htmlspecialchars(strip_tags($_POST['description']));
                    } else {
                        $description = null;
                    }

                    // Создаём объект класса Picture
                    $photo = new Picture(
                        $_FILES['photo']['type'],
                        $_FILES['photo']['tmp_name'],
                        $_FILES['photo']['size'],
                        $_FILES['photo']['error'],
                        $_FILES['photo']['name']
                    );

                    // Проверяем данные полученного изображения
                    if (!$photo->checkType()) {
                        $errors['photo'][] = 'Формат изображения должен быть типа jpeg, png или gif';
                    }
                    if (!$photo->checkSize()) {
                        $errors['photo'][] = 'Размер изображения должен быть меньше 1 Мб';
                    }
                    if (!$photo->checkError()) {
                        $errors['photo'][] = 'Ошибка при загрузке изображения № ' . $_FILES['photo']['error'];
                    }

                    // Если ошибок нет, добавляем товар в бд
                    if (empty($errors)) {
                        if ($photo->save()) {
                            if (self::getGoodsObj()->addNewGood(
                                $name,
                                $price,
                                $categoryId,
                                $photo->getImgAddress(),
                                $photo->getImgThumbAddress(),
                                $status,
                                $description
                            )) {
                                header("Location: /admin/goods/");
                            }
                        }
                    } else {
                        echo Templater::viewInclude(
                            './views/admin/goods/create.php',
                            [
                                'title' => $title,
                                'categoryList' => $categoryList,
                                'errors' => $errors,
                                'name' => $name,
                                'price' => $price,
                                'categoryId' => $categoryId,
                                'status' => $status,
                                'description' => $description,
                            ]
                        );
                    }
                    return true;
                } else {
                    echo Templater::viewInclude(
                        './views/admin/goods/create.php',
                        [
                            'title' => $title,
                            'categoryList' => $categoryList,
                        ]
                    );
                }
                return true;
            } else {
                self::showError('Отказ в доступе');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }

    /**
     * Изменяем данные о товаре
     * @param $goodId
     * @return bool|void
     */
    public function actionUpdate($goodId)
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Проверяем является ли пользователь администратором
            if (self::checkAdmin()) {
                // Список категорий товаров
                $categoryList = self::getGoodsObj()->getCategoryList();

                // Получаем данные о товаре
                $good = self::getGoodsObj()->getGoodById($goodId);

                //Титул страницы
                $title = $good['name'];

                // Переменные для формы
                $name = $good['name'];
                $price = $good['price'];
                $categoryId = $good['id_category'];
                $status = $good['status'];
                $description = $good['description'];
                $imgThumbAddress = $good['img_thumb_address'];
                $imgAddress = $good['img_address'];

                if (isset($_POST['submit'])) {
                    // Переменные для формы
                    $name = (string)htmlspecialchars(strip_tags($_POST['name']));
                    $price = (float)htmlspecialchars(strip_tags($_POST['price']));
                    $categoryId = (int)htmlspecialchars(strip_tags($_POST['categoryId']));
                    $status = (int)htmlspecialchars(strip_tags($_POST['status']));

                    // Проверяем полученный данные от пользователя
                    if (self::getGoodsObj()->checkGoodExists($name) && $name != $good['name']) {
                        $errors['name'] = 'Товар с таким наименованием уже существует';
                    }
                    if (!self::getGoodsObj()->checkPrice($price)) {
                        $errors['price'] = 'Цена должна быть больше 0';
                    }
                    if (isset($_POST['description'])) {
                        $description = (string)htmlspecialchars(strip_tags($_POST['description']));
                    } else {
                        $description = null;
                    }

                    if (!empty($_FILES['photo']['tmp_name'])) {
                        // Создаём объект класса Picture
                        $photo = new Picture(
                            $_FILES['photo']['type'],
                            $_FILES['photo']['tmp_name'],
                            $_FILES['photo']['size'],
                            $_FILES['photo']['error'],
                            $_FILES['photo']['name']
                        );

                        // Проверяем данные полученного изображения
                        if (!$photo->checkType()) {
                            $errors['photo'][] = 'Формат изображения должен быть типа jpeg, png или gif';
                        }
                        if (!$photo->checkSize()) {
                            $errors['photo'][] = 'Размер изображения должен быть меньше 1 Мб';
                        }
                        if (!$photo->checkError()) {
                            $errors['photo'][] = 'Ошибка при загрузке изображения № ' . $_FILES['photo']['error'];
                        }

                        // Если ошибок нет, сохраняем изображения
                        if (empty($errors)) {
                            if ($photo->save()) {
                                // Удаляем старые изображения из директории upload
                                unlink($imgThumbAddress);
                                unlink($imgAddress);
                                // Новые адреса изображений товара
                                $imgAddress = $photo->getImgAddress();
                                $imgThumbAddress = $photo->getImgThumbAddress();
                            }
                        }
                    }

                    // Если ошибок нет, добавляем товар в бд
                    if (empty($errors)) {
                        if (self::getGoodsObj()->updateGood(
                                $goodId,
                                $name,
                                $price,
                                $categoryId,
                                $status,
                                $description,
                                $imgAddress,
                                $imgThumbAddress
                            ) == 1) {
                            header("Location: /admin/goods/");
                        }
                    } else {
                        echo Templater::viewInclude(
                            './views/admin/goods/update.php',
                            [
                                'title' => $title,
                                'categoryList' => $categoryList,
                                'errors' => $errors,
                                'name' => $name,
                                'price' => $price,
                                'categoryId' => $categoryId,
                                'status' => $status,
                                'description' => $description,
                                'imgAddress' => $imgAddress,
                                'imgThumbAddress' => $imgThumbAddress,
                            ]
                        );
                    }
                    return true;
                } else {
                    echo Templater::viewInclude(
                        './views/admin/goods/update.php',
                        [
                            'title' => $title,
                            'categoryList' => $categoryList,
                            'name' => $name,
                            'price' => $price,
                            'categoryId' => $categoryId,
                            'status' => $status,
                            'description' => $description,
                            'imgAddress' => $imgAddress,
                            'imgThumbAddress' => $imgThumbAddress,
                        ]
                    );
                }
                return true;
            } else {
                self::showError('Отказ в доступе');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }

    /**
     * Удаляем товар
     * @param $goodId
     * @return bool|void
     */
    public function actionDelete($goodId)
    {
        // Проверяем является ли пользователь гостем
        if (!User::isGuest()) {
            // Проверяем является ли пользователь администратором
            if (self::checkAdmin()) {
                // Получаем данные о товаре
                $good = self::getGoodsObj()->getGoodById($goodId);
                if (!empty($good)) {
                    // Удаляем товар из БД
                    if (self::getGoodsObj()->deleteGood($goodId) == 1) {
                        // Удаляем изображения из директории upload
                        unlink($good['img_address']);
                        unlink($good['img_thumb_address']);
                        // Обновляем страницу
                        header("Location: /admin/goods/index");
                    }
                } else {
                    self::showError('Ошибка удаления товара');
                }
            } else {
                self::showError('Отказ в доступе');
            }
        } else {
            self::showError('Необходимо войти на сайт');
        }
    }
}
