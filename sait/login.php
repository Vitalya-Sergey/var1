<?php
session_start();
    $db = new PDO(
        'mysql:host=localhost;dbname=module;charset=utf8', 
        'root',
         null, 
        [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );

    if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
       $token = $_SESSION['token'];

    $user = $db->query("SELECT id, type FROM users WHERE token = '$token'")->fetchAll();
    
    if (!empty($user)) {
        $user_type = $user[0]['type'];
        $isAdmin = $user_type === 'admin';
        $isUser = $user_type === 'user';

        $isAdmin && header("Location: admin.php");
        $isUser && header("Location: user.php");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    if (empty($login) || empty($password)) {
        echo "Ошибка: поля необходимо заполнить";
        exit();
    }

    $user = $db->query("SELECT id, type FROM users WHERE login = '$login' AND password = '$password'")->fetchAll();
    if (!empty($user)) {
        $token = bin2hex(random_bytes(16));
        $userId = $user[0]['id'];
        
        $stmt = $db->prepare("UPDATE users SET token = ? WHERE id = ?");
        $stmt->execute([$token, $userId]);
        $_SESSION['token'] = $token;

        if ($user[0]['type'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: user.php");
        }
        exit();
    } else {
        echo "Ошибка: неверный логин или пароль";
        exit();
    }
}   
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отель - Вход в систему</title>
    <link rel="stylesheet" href="style/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Отель</h1>
            
            <div class="login-form">
                <h2>Войти в аккаунт</h2>
                <p class="subtitle">введите свой логин и пароль</p>
                
                <div class="user-type">
                    <input type="radio" id="admin" name="user_type" value="admin" class="hidden-radio">
                    <label for="admin" class="type-label admin">Администратор</label>
                    <span class="separator">/</span>
                    <input type="radio" id="user" name="user_type" value="user" class="hidden-radio" checked>
                    <label for="user" class="type-label user">пользователь</label>
                </div>

                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="login">Логин: <span class="required">это поле обязательное*</span></label> 
                        <input type="text" name="login" placeholder="логин" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль: <span class="required">это поле обязательное*</span></label> 
                        <input type="password" name="password" placeholder="пароль" required>
                    </div>
                    <button type="submit">Войти</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 