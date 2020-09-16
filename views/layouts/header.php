<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/template/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa|Russo+One" rel="stylesheet">
    <title><?= $title ?></title>
</head>
<body>
<div class="container">
    <div class="header">
        <ul class="menu">
            <li><a href="/">Главная</a></li>
            <?php if (\Demo\models\User::isGuest()): ?>
                <li><a href="/user/registration/">Регистрация</a></li>
                <li><a href="/user/login/">Вход</a></li>
            <?php else: ?>
                <li><a href="/cabinet/">Кабинет</a></li>
                <li><a href="/basket/">Корзина</a></li>
                <li><a href="/user/logout/">Выход</a></li>
                <?php if (\Demo\controllers\AdminBase::checkAdmin()): ?>
                    <li><a href="/admin/">Админпанель</a></>
                <?php endif; ?>
            <?php endif; ?>
        </ul>
    </div>
