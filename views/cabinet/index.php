<?php require_once './views/layouts/header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="profile">
            <div class="profile_info">
                <p><b>Ваше имя:</b> <?= $user['name'] ?></p>
                <p><b>Ваша почта:</b> <?= $user['email'] ?></p>
                <p><b>Последние посещённый страницы:</b></p>
                <?php foreach ($lastActions as $action): ?>
                    <p><?= $action; ?></p>
                <?php endforeach; ?>
            </div>
            <div class="orders_list">
                <p><a href="/order"><b>Последние заказы (Полный список):</b></a></p>
                <?php foreach ($lastOrders as $order): ?>
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
            </div>
        </div>
    </div>

<?php require_once './views/layouts/footer.php'; ?>