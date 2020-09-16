<?php require_once './views/layouts/header.php'; ?>

    <div class="content">
        <span class="caption"><?= $title ?></span>
        <div class="login">
            <?php if (isset($errors['userId'])): ?>
                <p class="error"><?= $errors['userId']; ?></p>
            <?php endif; ?>
            <form action="#" method="post">
                <?php if (isset($errors['email'])): ?>
                    <p class="error"><?= $errors['email']; ?></p>
                <?php endif; ?>
                <label><p>Email:</p>
                    <input type="email" name="email" placeholder="E-mail" value="<?= $email; ?>" required autofocus>
                </label>
                <?php if (isset($errors['password'])): ?>
                    <p class="error"><?= $errors['password']; ?></p>
                <?php endif; ?>
                <label><p>Пароль:</p>
                    <input type="password" name="password" placeholder="Пароль" value="<?= $password; ?>" required>
                </label>
                <input type="submit" name="submit" value="Войти"/>
            </form>
        </div>
    </div>

<?php require_once './views/layouts/footer.php'; ?>