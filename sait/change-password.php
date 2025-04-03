<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отель - Смена пароля</title>
    <link rel="stylesheet" href="style/CP.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Отель</h1>
            
            <div class="login-form">
                <h2>Сменить пароль</h2>
                <p class="subtitle">Сменить свой пароль</p>

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