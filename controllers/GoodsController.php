<?php

namespace Demo\controllers;

use Demo\components\Templater;

/**
 * Controller для работы с товарами
 * Class GoodsController
 */
class GoodsController extends BaseController
{
    /**
     * Просмотр одного товара
     * @param $goodId
     * @return bool|void
     */
    public function actionView($goodId)
    {
        // Получаем данные о товаре
        $good = self::getGoodsObj()->getGoodById($goodId);
        if (!empty($good)) {
            //Титул страницы
            $title = $good['name'];
            // Выводим
            echo Templater::viewInclude(
                '../views/goods/view.php',
                [
                    'title' => $title,
                    'good' => $good
                ]
            );
            return true;
        } else {
            self::showError('Такого товара не существует');
        }
    }
}
