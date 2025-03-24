<?php
require_once 'includes/auth.php';

if (!isAuthenticated()) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = trim($_POST["current_password"]);
    $newPassword = trim($_POST["new_password"]);
    $confirmPassword = trim($_POST["confirm_password"]);
    
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $error = "Пожалуйста, заполните все поля";
    } elseif ($newPassword !== $confirmPassword) {
        $error = "Новый пароль и подтверждение не совпадают";
    } else {
        $result = changePassword($_SESSION['user_id'], $currentPassword, $newPassword);
        if ($result["success"]) {
            $success = $result["message"];
            header("refresh:2;url=dashboard.php");
        } else {
            $error = $result["message"];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Смена пароля</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Смена пароля</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="current_password">Текущий пароль:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">Новый пароль:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Подтверждение нового пароля:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn">Изменить пароль</button>
        </form>
    </div>
</body>
</html> 