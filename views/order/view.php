<?php require_once '../views/layouts/header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="order">
            <p>Заказ № <b><?= $orderId; ?></b> от <?= $created; ?></p>
            <div>
                <table>
                    <thead>
                    <tr>
                        <td>Товар</td>
                        <td>Цена</td>
                        <td>Статус</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= $order['good_name'] ?></td>
                            <td><?= $order['price'] ?> &#x20bd;</td>
                            <td>
                                <?php if ($order['status_id'] == 4): ?>
                                    <span class="done"> <?= $order['status'] ?></span>
                                <?php else: ?>
                                    <span> <?= $order['status'] ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php require_once '../views/layouts/footer.php'; ?>