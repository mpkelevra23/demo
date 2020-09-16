<?php require_once './views/layouts/admin_header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="goods">
            <?php if (!empty($goodsList)): ?>
                <table>
                    <thead>
                    <tr>
                        <td>id товара</td>
                        <td>Наименование</td>
                        <td>Цена</td>
                        <td>Изображение</td>
                        <td>Категория товаров</td>
                        <td>Наличие товара</td>
                        <td>Описание</td>
                        <td>Редактировать</td>
                        <td>Удалить</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($goodsList as $goods): ?>
                        <tr>
                            <td><?= $goods['id'] ?></td>
                            <td><?= $goods['name'] ?></td>
                            <td><?= $goods['price'] ?></td>
                            <td>
                                <a href="/<?= $goods['img_address'] ?>">
                                    <img class="admin_good_picture" src="/<?= $goods['img_thumb_address'] ?>"
                                         alt="<?= $goods['name'] ?>">
                                </a>
                            </td>
                            <td><?= $goods['category'] ?></td>
                            <?php if ($goods['status'] == 1) : ?>
                                <td>В наличии</td>
                            <?php else : ?>
                                <td>Закончился</td>
                            <?php endif; ?>
                            <td>
                                <details>
                                    <summary>Читать...</summary><?= $goods['description'] ?></details>
                            </td>
                            <td><a href="/admin/goods/update/<?= $goods['id'] ?>">изменить</a></td>
                            <td><a href="/admin/goods/delete/<?= $goods['id'] ?>">&#10006;</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            <?php else: ?>
                <p>Список товаров пуст</p>
            <?php endif; ?>
            <a href="/admin/goods/create">
                <button>Создать товар</button>
            </a>
        </div>
    </div>

<?php require_once './views/layouts/admin_footer.php'; ?>