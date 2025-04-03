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

                <form action="" method="post">
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