<?php require_once '../views/layouts/admin_header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="admin_orders">
            <?php if (!empty($orderList)): ?>
                <table>
                    <thead>
                    <tr>
                        <td>Номер заказа</td>
                        <td>Имя пользователя</td>
                        <td>Почта пользователя</td>
                        <td>Дата заказа</td>
                        <td>Стоймость заказа</td>
                        <td>Редактировать</td>
                        <td>Удалить</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orderList as $order): ?>
                        <tr>
                            <td><?= $order['id'] ?></td>
                            <td><?= $order['user_name'] ?></td>
                            <td><?= $order['user_email'] ?></td>
                            <td><?= $order['created'] ?></td>
                            <td><?= $order['total_price'] ?> &#x20bd;</td>
                            <td>
                                <form action="#" method="post" autofocus>
                                    <label>
                                        <select name="status" form="order" required onchange=changeStatus(this.value)>
                                            <?php foreach ($orderStatus as $status): ?>
                                                <?php if ($status['id'] === $order['status_id']) : ?>
                                                    <option selected
                                                            value="<?= $order['id']; ?>, <?= $status['id']; ?>"><?= $status['name']; ?></option>
                                                <?php else : ?>
                                                    <option value="<?= $order['id']; ?>, <?= $status['id']; ?>"><?= $status['name']; ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                </form>
                            </td>
                            <td><a href="/admin/order/delete/<?= $order['id'] ?>">&#10006;</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            <?php else: ?>
                <p>Список заказов пуст</p>
            <?php endif; ?>
        </div>
    </div>

<?php require_once '../views/layouts/admin_footer.php'; ?>