<?php

namespace Demo\components;

/**
 * Component для контроля вывода
 * Class Templater
 */
final class Templater
{
    /**
     * @param $file
     * @param array $params
     * @return bool|false|string
     */
    public static function viewInclude($file, $params = [])
    {
        // Установка переменных для шаблона.
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        // Генерация HTML в строку.
        if (is_file($file)) {
            ob_start();
            require_once $file;
            return ob_get_clean();
        } else {
            return false;
        }
    }
}
