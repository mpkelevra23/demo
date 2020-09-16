<?php require_once './views/layouts/header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="registration">
            <form action="#" method="post">
                <?php if (isset($errors['name'])): ?>
                    <p class="error"><?= $errors['name']; ?></p>
                <?php endif; ?>
                <label><p>Имя:</p>
                    <input type="text" name="name" placeholder="Имя" value="<?= $name; ?>" required autofocus>
                </label>
                <?php if (isset($errors['email'])): ?>
                    <p class="error"><?= $errors['email']; ?></p>
                <?php endif; ?>
                <label><p>Email:</p>
                    <input type="text" name="email" placeholder="Email" value="<?= $email; ?>" required>
                </label>
                <?php if (isset($errors['password'])): ?>
                    <p class="error"><?= $errors['password']; ?></p>
                <?php endif; ?>
                <label><p>Пароль:</p>
                    <input type="password" name="password" placeholder="Пароль" value="<?= $password; ?>" required>
                </label>
                <input type="submit" name="submit" value="Регистрация">
            </form>
        </div>
    </div>

<?php require_once './views/layouts/footer.php'; ?>