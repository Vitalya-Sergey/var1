<?php
session_start();
    $db = new PDO(
        'mysql:host=localhost;dbname=module;charset=utf8', 
        'root',
         null, 
        [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    if (!isset($_SESSION['token']) || empty($_SESSION['token'])) {
        header("Location: login.php");
        exit();
    }

    $token = $_SESSION['token'];
    $user = $db->query("SELECT id, type, name, surname FROM users WHERE token = '$token'")->fetchAll();

    if (empty($user)) {
        header("Location: login.php");
        exit();
    }

    $user_type = $user[0]['type'];
    if ($user_type === 'admin') {
        header("Location: admin.php");
        exit();
    }

if (isset($_GET['do']) && $_GET['do'] === 'logout') {
    $stmt = $db->prepare("UPDATE users SET token = NULL WHERE token = ?");
    $stmt->execute([$token]);
    $_SESSION['token'] = null;
    unset($_SESSION['token']);
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
            echo "Все поля должны быть заполнены";
            exit();
        }
        if ($new_password !== $confirm_password) {
            echo "Новые пароли не совпадают";
            exit();
        }
        $user_id = $user[0]['id'];
        $check_old = $db->query("SELECT id FROM users WHERE id = '$user_id' AND password = '$old_password'")->fetchAll();
        
        if (empty($check_old)) {
            echo "Неверный старый пароль";
            exit();
        }

        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($stmt->execute([$new_password, $user_id])) {
            echo "Пароль успешно изменен";
        } else {
            echo "Ошибка при смене пароля";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отель - Пользователь</title>
    <link rel="stylesheet" href="style/user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header class="header">
        <div class="container">
            <p class="header_admin"><?php echo htmlspecialchars($user[0]['name'] . ' ' . $user[0]['surname']); ?></p>
            <a class="header_login" href="?do=logout">Выйти <i class="fa fa-sign-out" aria-hidden="true"></i>
            </a>
        </div>
    </header>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Отель</h1>
            <div class="login-form">
                <h2>Сменить пароль</h2>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="old_password">Старый пароль: <span class="required">это поле обязательное*</span></label> 
                        <input type="password" name="old_password" placeholder="введите старый пароль" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Новый пароль: <span class="required">это поле обязательное*</span></label> 
                        <input type="password" name="new_password" placeholder="введите новый пароль" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Подтвердите пароль: <span class="required">это поле обязательное*</span></label> 
                        <input type="password" name="confirm_password" placeholder="подтвердите пароль" required>
                    </div>
                        <button type="submit">Сменить пароль</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 
</body>
</html> 