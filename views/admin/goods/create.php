<?php require_once './views/layouts/admin_header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="create">
            <form enctype="multipart/form-data" action="#" method="post">
                <?php if (isset($errors['name'])): ?>
                    <p class="error"><?= $errors['name']; ?></p>
                <?php endif; ?>
                <label><p>Наименование тоара:</p>
                    <input name="name" type="text" placeholder="Наименование тоара"
                           value="<?php if (isset($name)) print $name ?>" required autofocus>
                </label>
                <?php if (isset($errors['price'])): ?>
                    <p class="error"><?= $errors['price']; ?></p>
                <?php endif; ?>
                <label><p>Стоймость тоара:</p>
                    <input name="price" type="text" placeholder="Стоймость тоара"
                           value="<?php if (isset($price)) print $price ?>" required>
                </label>
                <label><p>Категория товара:</p>
                    <select name="categoryId" required>
                        <?php if (isset($categoryId)): ?>
                            <?php foreach ($categoryList as $category): ?>
                                <?php if ($category['id'] == $categoryId): ?>
                                    <option value="<?= $category['id']; ?>"
                                            selected><?= $category['name']; ?></option>
                                <?php else: ?>
                                    <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option></option>
                            <?php foreach ($categoryList as $category): ?>
                                <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </label>
                <label><p>Статус товара:</p>
                    <select name="status" required>
                        <?php if (isset($status)): ?>
                            <?php if ($status == true): ?>
                                <option value="1" selected>В наличии</option>
                                <option value="0">Отсутствует</option>
                            <?php elseif ($status == false): ?>
                                <option value="1">В наличии</option>
                                <option value="0" selected>Отсутствует</option>>
                            <?php endif; ?>
                        <?php else: ?>
                            <option></option>
                            <option value="1">В наличии</option>
                            <option value="0">Отсутствует</option>
                        <?php endif; ?>
                    </select>
                </label>
                <label><p>Описание товара:</p>
                    <textarea name="description" cols="50" rows="30">
                        <?php if (isset($description)) print $description ?>
                    </textarea>
                </label>
                <?php if (isset($errors['photo'])): ?>
                    <?php foreach ($errors['photo'] as $error): ?>
                        <p class="error"><?= $error; ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
                <label><p>Выберите изображение товара:</p>
                    <input name="photo" type="file" required>
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                </label>
                <input type="submit" name="submit" value="Создать">
            </form>
        </div>
    </div>

<?php require_once './views/layouts/admin_footer.php'; ?>