<?php
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    require_once 'users.php';
    require_once 'logger.php';
    if (
        isset($users[$login]) &&
        password_verify($password, $users[$login]['password_hash'])
    ) {
        $_SESSION['user'] = $login;
        $_SESSION['role'] = $users[$login]['role'];
        $_SESSION['user_id'] = $users[$login]['id'];
        writeLog($login, 'LOGIN');
        header('Location: index.php');
        exit;
    } else {
        writeLog($login ?: 'unknown', 'FAIL_LOGIN');
        $error = 'Неверный логин или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">

<main>
    <div class="auth-box">

        <h2>Вход в систему</h2>
        <form method="POST">
            <label>
                Логин
                <input type="text" name="login" required>
            </label>
            <label>
                Пароль
                <input type="password" name="password" required>
            </label>

            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>
            <button type="submit">
                Войти
            </button>

        </form>
    </div>
</main>
</body>
</html>
