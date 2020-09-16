<?php

namespace Demo\components;

/**
 * Component для работы с изображениями
 * Class Picture
 */
class Picture
{
    private const ADDRESS = './upload/img/';
    private const THUMB_ADDRESS = './upload/thumb/';

    private $imgAddress;
    private $imgThumbAddress;

    private $type;
    private $tmpName;
    private $size;
    private $error;
    private $name;

    public function __construct($type, $tmpName, $size, $error, $name)
    {
        $this->setType($type);
        $this->setTmpName($tmpName);
        $this->setSize($size);
        $this->setError($error);
        $this->setName($name);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param $type
     * @return string
     */
    public function setType($type): string
    {
        return $this->type = (string)htmlspecialchars(strip_tags($type));
    }

    /**
     * @return mixed
     */
    public function getTmpName()
    {
        return $this->tmpName;
    }

    /**
     * @param $tmpName
     * @return string
     */
    public function setTmpName($tmpName): string
    {
        return $this->tmpName = (string)htmlspecialchars(strip_tags($tmpName));
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param $size
     * @return int
     */
    public function setSize($size): int
    {
        return $this->size = (int)htmlspecialchars(strip_tags($size));
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $error
     * @return int
     */
    public function setError($error): int
    {
        return $this->error = (int)htmlspecialchars(strip_tags($error));
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return string
     */
    public function setName($name): string
    {
        return $this->name = (string)htmlspecialchars(strip_tags($name));
    }

    /**
     * @return mixed
     */
    public function getImgAddress()
    {
        return $this->imgAddress;
    }

    /**
     * @param $imgAddress
     * @return string
     */
    public function setImgAddress($imgAddress): string
    {
        return $this->imgAddress = $imgAddress;
    }

    /**
     * @return mixed
     */
    public function getImgThumbAddress()
    {
        return $this->imgThumbAddress;
    }

    /**
     * @param $imgThumbAddress
     * @return string
     */
    public function setImgThumbAddress($imgThumbAddress): string
    {
        return $this->imgThumbAddress = $imgThumbAddress;
    }

    /**
     * Сохраняем изображение
     * @return bool
     */
    public function save()
    {
        // Имя изображения
        $this->setName(Transfer::getTranslate(basename($this->name)));

        // Если изображение успешно перемещено, то создаём аватар
        if (is_uploaded_file($this->tmpName)) {
            if (move_uploaded_file($this->tmpName, self::ADDRESS . $this->name)) {
                // Если аватар изображения успешно создан, то сохраняем пути
                if (self::createThumb(
                    200,
                    200,
                    self::ADDRESS . $this->name,
                    self::THUMB_ADDRESS . $this->name,
                    $this->type
                )) {
                    // Адрес изображения
                    $this->setImgAddress(self::ADDRESS . $this->name);
                    // Адрес аватарки
                    $this->setImgThumbAddress(self::THUMB_ADDRESS . $this->name);
                    return true;
                }
                return false;
            }
            return false;
        }
        return false;
    }

    /**
     * Создаём аватар изображения
     * @param $height
     * @param $width
     * @param $src
     * @param $new_src
     * @param $type
     * @return bool
     */
    private function createThumb($height, $width, $src, $new_src, $type)
    {
        // Должен быть установлен пакет функций для работы с изображениями gd, sudo apt-get install php7.2-gd
        $new_img = imagecreatetruecolor($height, $width);
        switch ($type) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg($src);
                imagecopyresampled($new_img, $img, 0, 0, 0, 0, $height, $width, imagesx($img), imagesy($img));
                imagejpeg($new_img, $new_src);
                return true;
            case 'image/png':
                $img = imagecreatefrompng($src);
                imagecopyresampled($new_img, $img, 0, 0, 0, 0, $height, $width, imagesx($img), imagesy($img));
                imagepng($new_img, $new_src);
                return true;
            case 'image/gif':
                $img = imagecreatefromgif($src);
                imagecopyresampled($new_img, $img, 0, 0, 0, 0, $height, $width, imagesx($img), imagesy($img));
                imagegif($new_img, $new_src);
                return true;
        }
        return false;
    }

    /**
     * Проверям наличие ошибок
     * @return bool
     */
    public function checkError()
    {
        if ($this->error == 0) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем размер изображения
     * @return bool
     */
    public function checkSize()
    {
        if ($this->size <= 10000000) {
            return true;
        }
        return false;
    }

    /**
     * Проверяем тип изображения
     * @return bool
     */
    public function checkType()
    {
        if ($this->type == 'image/jpeg' || $this->type == 'image/png' || $this->type == 'image/gif') {
            return true;
        }
        return false;
    }
}
