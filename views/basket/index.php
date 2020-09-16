<?php require_once './views/layouts/header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="basket">
            <?php if (!empty($goods)): ?>
                <table>
                    <thead>
                    <tr>
                        <td>Наименование</td>
                        <td>Цена</td>
                        <td>Удалить</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($goods as $product): ?>
                        <tr>
                            <td><?= $product['name'] ?></td>
                            <td><?= $product['price'] ?> &#x20bd;</td>
                            <td><a href="/basket/delete/<?= $product['basket_id'] ?>">&#10006;</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <td>Общая сумма</td>
                        <td><?= $totalPrice; ?> &#x20bd;</td>
                    </tr>
                    </tfoot>
                </table>
                <a href="/order/create">
                    <button>Оформить заказ</button>
                </a>
            <?php else: ?>
                <p><b>Корзина пуста</b></p>
            <?php endif; ?>
        </div>
    </div>

<?php require_once './views/layouts/footer.php'; ?>