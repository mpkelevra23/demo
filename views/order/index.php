<?php require_once './views/layouts/header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="orders">
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <p>
                        <a href="/order/view/<?= $order['id']; ?>">Заказ №
                            <b><?= $order['id']; ?></b> от <i><?= $order['created']; ?></i></a>,
                        <?php if ($order['status_id'] == 4): ?>
                            <span class="done"> <?= $order['status'] ?></span>
                        <?php else: ?>
                            <span> <?= $order['status'] ?></span>
                        <?php endif; ?>
                    </p>
                <?php endforeach; ?>
            <?php else: ?>
                <p><b>Вы не сделали ни одного заказа</b></p>
            <?php endif; ?>
        </div>
    </div>

<?php require_once './views/layouts/footer.php'; ?>