<?php require_once './views/layouts/header.php'; ?>

    <div class="content">
        <span class="caption">Каталог</span>
        <div class="position">
            <?php foreach ($goods as $good): ?>
                <a href="goods/view/<?= $good['id']; ?>"><p><?= $good['name']; ?></p>
                    <img class="picture" src="/<?= $good['img_thumb_address']; ?>" alt="<?= $good['name']; ?>">
                    <p><?= $good['price']; ?> &#x20bd;</p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

<?php require_once './views/layouts/footer.php'; ?>