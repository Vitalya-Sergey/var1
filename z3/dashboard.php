<?php
require_once 'includes/auth.php';

if (!isAuthenticated()) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

// Добавление нового пользователя
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {
    if ($_POST["action"] == "add_user" && isAdmin()) {
        $newLogin = trim($_POST["new_login"]);
        $newPassword = trim($_POST["new_password"]);
        $role = trim($_POST["role"]);
        
        if (empty($newLogin) || empty($newPassword) || empty($role)) {
            $error = "Пожалуйста, заполните все поля";
        } else {
            $sql = "SELECT id FROM users WHERE login = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $newLogin);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $error = "Пользователь с таким логином уже существует";
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (login, password, role, first_login) VALUES (?, ?, ?, 1)";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "sss", $newLogin, $hashedPassword, $role);
                
                if (mysqli_stmt_execute($stmt)) {
                    $success = "Пользователь успешно добавлен";
                } else {
                    $error = "Ошибка при добавлении пользователя";
                }
            }
        }
    } elseif ($_POST["action"] == "unblock_user" && isAdmin()) {
        $userId = $_POST["user_id"];
        $sql = "UPDATE users SET is_blocked = 0, login_attempts = 0 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        
        if (mysqli_stmt_execute($stmt)) {
            $success = "Пользователь разблокирован";
        } else {
            $error = "Ошибка при разблокировке пользователя";
        }
    }
}

// Получение списка пользователей для администратора
$users = [];
if (isAdmin()) {
    $sql = "SELECT * FROM users WHERE id != ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель управления</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Добро пожаловать, <?php echo $_SESSION['role']; ?></h2>
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isAdmin()): ?>
            <div class="admin-panel">
                <h3>Добавить нового пользователя</h3>
                <form method="post" action="">
                    <input type="hidden" name="action" value="add_user">
                    
                    <div class="form-group">
                        <label for="new_login">Логин:</label>
                        <input type="text" id="new_login" name="new_login" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">Пароль:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="role">Роль:</label>
                        <select id="role" name="role" required>
                            <option value="Пользователь">Пользователь</option>
                            <option value="Администратор">Администратор</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn">Добавить пользователя</button>
                </form>
                
                <h3>Список пользователей</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Логин</th>
                            <th>Роль</th>
                            <th>Статус</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['login']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td><?php echo $user['is_blocked'] ? 'Заблокирован' : 'Активен'; ?></td>
                                <td>
                                    <?php if ($user['is_blocked']): ?>
                                        <form method="post" action="" style="display: inline;">
                                            <input type="hidden" name="action" value="unblock_user">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="btn btn-small">Разблокировать</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
        
        <p><a href="logout.php" class="btn">Выйти</a></p>
    </div>
</body>
</html> 