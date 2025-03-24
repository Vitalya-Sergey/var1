<?php
session_start();
require_once 'config.php';

function loginUser($login, $password) {
    global $conn;
    
    // Проверка на блокировку
    $sql = "SELECT * FROM users WHERE login = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Проверка на блокировку
        if ($user['is_blocked']) {
            return ["success" => false, "message" => "Вы заблокированы. Обратитесь к администратору"];
        }

        // Проверка на неактивность более месяца
        $lastLogin = new DateTime($user['last_login']);
        $now = new DateTime();
        $interval = $lastLogin->diff($now);
        
        if ($interval->m >= 1) {
            $sql = "UPDATE users SET is_blocked = 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user['id']);
            mysqli_stmt_execute($stmt);
            return ["success" => false, "message" => "Аккаунт заблокирован из-за длительного отсутствия активности"];
        }

        // Проверка пароля
        if (password_verify($password, $user['password'])) {
            // Сброс счетчика неудачных попыток
            $sql = "UPDATE users SET login_attempts = 0, last_login = NOW() WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user['id']);
            mysqli_stmt_execute($stmt);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_login'] = $user['first_login'];

            return ["success" => true, "message" => "Вы успешно авторизовались", "first_login" => $user['first_login']];
        } else {
            // Увеличение счетчика неудачных попыток
            $attempts = $user['login_attempts'] + 1;
            $sql = "UPDATE users SET login_attempts = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ii", $attempts, $user['id']);
            mysqli_stmt_execute($stmt);

            // Блокировка после 3 неудачных попыток
            if ($attempts >= 3) {
                $sql = "UPDATE users SET is_blocked = 1 WHERE id = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $user['id']);
                mysqli_stmt_execute($stmt);
                return ["success" => false, "message" => "Вы заблокированы. Обратитесь к администратору"];
            }
        }
    }
    
    return ["success" => false, "message" => "Вы ввели неверный логин или пароль. Пожалуйста проверьте ещё раз введенные данные"];
}

function changePassword($userId, $currentPassword, $newPassword) {
    global $conn;
    
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (password_verify($currentPassword, $user['password'])) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = ?, first_login = 0 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $hashedPassword, $userId);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["success" => true, "message" => "Пароль успешно изменен"];
        }
    }
    
    return ["success" => false, "message" => "Неверный текущий пароль"];
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Администратор';
}

function isAuthenticated() {
    return isset($_SESSION['user_id']);
}
?> 