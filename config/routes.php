<?php

// Массив с путями (роутами)
return [
    // AdminOrder
    'admin/order/delete/([0-9]+)' => 'adminOrder/delete/$1', //actionDelete в AdminOrderController
    'admin/order/update/([0-9]+)' => 'adminOrder/update/$1', //actionUpdate в AdminOrderController
    'admin/order/ajaxUpdate' => 'adminOrder/ajaxUpdate', //actionAjaxUpdate в AdminOrderController
    'admin/order/index' => 'adminOrder/index', //actionIndex в AdminOrderController
    'admin/order' => 'adminOrder/index', //actionIndex в AdminOrderController
    // AdminGoods
    'admin/goods/delete/([0-9]+)' => 'adminGoods/delete/$1', //actionDelete в AdminGoodsController
    'admin/goods/update/([0-9]+)' => 'adminGoods/update/$1', //actionUpdate в AdminGoodsController
    'admin/goods/create' => 'adminGoods/create', //actionCreate в AdminGoodsController
    'admin/goods/index' => 'adminGoods/index', //actionIndex в AdminGoodsController
    'admin/goods' => 'adminGoods/index', //actionIndex в AdminGoodsController
    // Admin
    'admin/index' => 'admin/index', //actionIndex в AdminController
    'admin' => 'admin/index', //actionIndex в AdminController
    // Order
    'order/view/([0-9]+)' => 'order/view/$1', // actionView в OrderController
    'order/create' => 'order/create', // actionCreate в OrderController
    'order/index' => 'order/index', // actionIndex в OrderController
    'order' => 'order/index', // actionIndex в OrderController
    // Basket
    'basket/add/([0-9]+)' => 'basket/add/$1', // actionAdd в BasketController
    'basket/delete/([0-9]+)' => 'basket/delete/$1', //actionDelete в BasketController
    'basket/index' => 'basket/index', // actionIndex в BasketController
    'basket' => 'basket/index', // actionIndex в BasketController
    // Good
    'goods/view/([0-9]+)' => 'goods/view/$1', // actionView в GoodController
    // Cabinet
    'cabinet/index' => 'cabinet/index', //actionIndex в CabinetController
    'cabinet' => 'cabinet/index', //actionIndex в CabinetController
    // User
    'user/registration' => 'user/registration', //actionRegistration в UserController
    'user/login' => 'user/login', //actionLogin в UserController
    'user/logout' => 'user/logout', //actionLogin в UserController
    // Главная страница
    'index' => 'site/index', // actionIndex в SiteController
    '(.)+' => 'site/error', // actionError в SiteController
    '' => 'site/index', // actionIndex в SiteController
];
