<?php

namespace Demo\components;

/**
 * Class Router
 * Компонент для работы с маршрутами
 */
class Router
{
    /**
     * Метод для обработки запроса
     * Обрабатываем запрос пользователя и подключаем нужный метод в соответствующем классе
     */
    public static function run()
    {
        // Получить строку запроса
        $uri = self::getURI();

        // Проверяем наличие такого запроса в массиве маршрутов (routes.php)
        foreach (self::getRouts() as $uriPattern => $path) {
            // Сравниваем наши пути (роуты $uriPattern) с получинным от пользователя адресом $uri
            if (preg_match("~$uriPattern~", $uri)) {
                // Получаем внутренний путь из внешнего согласно правилу.
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                // Определяем имя контроллера, action и параметры из uri
                $segments = explode('/', $internalRoute);

                // Получаем имя контроллера
                $controllerName = 'Demo\\controllers\\' . ucfirst(array_shift($segments)) . 'Controller';

                // Получаем имя метода
                $actionName = 'action' . ucfirst(array_shift($segments));

                // Получаем параметры
                $parameters = $segments;

                // Создать объект, вызвать метод (т.е. action)
                $controllerObject = new $controllerName;

                /*
                 * Вызываем необходимый метод ($actionName) у определенного класса ($controllerObject) с заданными ($parameters) параметрами.
                 * Если метод контроллера успешно вызван и вкрнул true, завершаем работу роутера
                 */
                if (call_user_func_array([$controllerObject, $actionName], $parameters)) {
                    break;
                }
            }
        }
    }

    /**
     * Возвращаем запрашиваемый uri адрес
     * @return string
     */
    public static function getURI(): string
    {
        return trim($_SERVER['REQUEST_URI'], '/');
    }

    /**
     * Возвращаем массив с путями (роутами)
     * @return array
     */
    private static function getRouts(): array
    {
        $routesPath = '../config/routes.php';
        return include($routesPath);
    }
}
