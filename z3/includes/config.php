<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'auth_system');

// Подключение к базе данных
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Проверка соединения
if($conn === false){
    die("ОШИБКА: Не удалось подключиться. " . mysqli_connect_error());
}
?> 