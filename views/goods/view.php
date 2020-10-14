<?php require_once '../views/layouts/header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="info">
            <div class="info_picture">
                <a href="/<?= $good['img_address'] ?>">
                    <img class="description_picture" src="/<?= $good['img_address'] ?>" alt="<?= $good['name'] ?>">
                </a>
            </div>
            <div class="description">
                <p class="short_description"><?= $good['description'] ?></p>
                <p><?= $good['price'] ?> &#x20bd;</p>
                <?php if (!\Demo\models\User::isGuest()): ?>
                    <a href="/basket/add/<?= $good['id']; ?>" class="button">
                        <button>Добавить в корзину</button>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php require_once '../views/layouts/footer.php'; ?>