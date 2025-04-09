<?php
session_start();
$db = new PDO(
    'mysql:host=localhost;dbname=module;charset=utf8', 
    'root',
    null, 
    [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

// Handle logout
if (isset($_GET['do']) && $_GET['do'] === 'logout') {
    $stmt = $db->prepare("UPDATE users SET token = NULL WHERE token = ?");
    $stmt->execute([$_SESSION['token']]);
    $_SESSION['token'] = null;
    unset($_SESSION['token']);
    header("Location: login.php");
    exit();
}

// Check authentication
if (!isset($_SESSION['token']) || empty($_SESSION['token'])) {
    header("Location: login.php");
    exit();
}

$token = $_SESSION['token'];
$user = $db->query("SELECT id, type, name, surname, blocked, latest FROM users WHERE token = '$token'")->fetchAll();

if (empty($user)) {
    header("Location: login.php");
    exit();
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    // Verify admin
    $admin = $db->query("SELECT type FROM users WHERE token = '$token'")->fetch();
    if (!$admin || $admin['type'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Access denied']);
        exit();
    }

    switch ($_POST['action']) {
        case 'get_user':
            if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
                exit();
            }
            $stmt = $db->prepare("SELECT id, name, surname, login, type FROM users WHERE id = ?");
            $stmt->execute([$_POST['user_id']]);
            $user = $stmt->fetch();
            echo json_encode(['success' => true, 'user' => $user]);
            exit();
            break;

        case 'save_user':
            $required_fields = ['name', 'surname', 'login', 'type'];
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                    exit();
                }
            }

            $userId = isset($_POST['user_id']) ? $_POST['user_id'] : null;
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $login = $_POST['login'];
            $type = $_POST['type'];

            // Check if login is already taken
            $stmt = $db->prepare("SELECT id FROM users WHERE login = ? AND id != ?");
            $stmt->execute([$login, $userId ?? 0]);
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'Login already exists']);
                exit();
            }

            if ($userId) {
                // Update existing user
                if (isset($_POST['password']) && !empty($_POST['password'])) {
                    $password = $_POST['password'];
                    $stmt = $db->prepare("UPDATE users SET name = ?, surname = ?, login = ?, password = ?, type = ? WHERE id = ?");
                    $result = $stmt->execute([$name, $surname, $login, $password, $type, $userId]);
                } else {
                    $stmt = $db->prepare("UPDATE users SET name = ?, surname = ?, login = ?, type = ? WHERE id = ?");
                    $result = $stmt->execute([$name, $surname, $login, $type, $userId]);
                }
            } else {
                // Create new user
                if (!isset($_POST['password']) || empty($_POST['password'])) {
                    echo json_encode(['success' => false, 'message' => 'Password is required for new users']);
                    exit();
                }
                $password = $_POST['password'];
                $stmt = $db->prepare("INSERT INTO users (name, surname, login, password, type, blocked) VALUES (?, ?, ?, ?, ?, '0')");
                $result = $stmt->execute([$name, $surname, $login, $password, $type]);
            }
            echo json_encode(['success' => $result]);
            exit();
            break;

        case 'toggle_block':
            if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
                exit();
            }
            $userId = $_POST['user_id'];
            $user = $db->query("SELECT blocked FROM users WHERE id = $userId")->fetch();
            if (!$user) {
                echo json_encode(['success' => false, 'message' => 'User not found']);
                exit();
            }
            $newStatus = $user['blocked'] == '1' ? '0' : '1';
            $stmt = $db->prepare("UPDATE users SET blocked = ? WHERE id = ?");
            $result = $stmt->execute([$newStatus, $userId]);
            echo json_encode(['success' => $result]);
            exit();
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отель - Админка</title>
    <link rel="stylesheet" href="style/admin.css">  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header class="header">
    <div class="container">
        <p class="header_admin">Админ_имя</p>
        <a class="header_login" href="?do=logout">Выйти <i class="fa fa-sign-out" aria-hidden="true"></i></a>
    </div>
</header>

<section class="clients">
    <h2 class="clients_title">Список пользователей</h2>  
    <div class="clients_list">
        <table>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Логин</th>
                <th>Тип</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            <?php
            $users = $db->query("SELECT * FROM users ORDER BY id")->fetchAll();
            foreach ($users as $user) {
                $status = $user['blocked'] == '1' ? 'Заблокирован' : 'Активен';
                $buttonText = $user['blocked'] == '1' ? 'Разблокировать' : 'Заблокировать';
                $buttonClass = $user['blocked'] == '1' ? 'unblock' : 'block';
                ?>
                <tr> 
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['surname']); ?></td>
                    <td><?php echo htmlspecialchars($user['login']); ?></td>
                    <td><?php echo htmlspecialchars($user['type']); ?></td>
                    <td><?php echo $status; ?></td>
                    <td>
                        <button class="action-button edit" onclick="editUser(<?php echo $user['id']; ?>)">Редактировать</button>
                        <button class="action-button <?php echo $buttonClass; ?>" 
                                onclick="toggleBlock(<?php echo $user['id']; ?>)">
                            <?php echo $buttonText; ?>
                        </button>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</section>

<section class="clients">
    <h2 class="clients_title" id="form_title">Добавить пользователя</h2>  
    <div class="client_add">
        <div class="client_add_form">
            <form id="user_form" method="POST">
                <input type="hidden" name="action" value="save_user">
                <input type="hidden" name="user_id" id="user_id" value="">
                
                <label for="surname">Фамилия:</label> 
                <input type="text" name="surname" id="surname" placeholder="фамилия" required>

                <label for="name">Имя:</label> 
                <input type="text" name="name" id="name" placeholder="имя" required>

                <label for="login">Логин: <span class="required">*</span></label> 
                <input type="text" name="login" id="login" placeholder="логин" required>

                <label for="password">Пароль: <span class="required">*</span></label> 
                <input type="password" name="password" id="password" placeholder="пароль" required>

                <label for="type">Тип пользователя: <span class="required">*</span></label>
                <select name="type" id="type" required>
                    <option value="user">Пользователь</option>
                    <option value="admin">Администратор</option>
                </select>

                <button type="submit" class="add_client" id="submit_button">Добавить пользователя</button>
                <button type="button" class="cancel_edit" id="cancel_edit" style="display: none;">Отменить редактирование</button>
            </form>
        </div>
    </div>
</section>

<script>
function toggleBlock(userId) {
    if (confirm('Вы уверены, что хотите изменить статус пользователя?')) {
        fetch('admin.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=toggle_block&user_id=' + userId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Ошибка при изменении статуса пользователя');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Произошла ошибка при выполнении запроса');
        });
    }
}

function editUser(userId) {
    fetch('admin.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_user&user_id=' + userId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('user_id').value = data.user.id;
            document.getElementById('surname').value = data.user.surname;
            document.getElementById('name').value = data.user.name;
            document.getElementById('login').value = data.user.login;
            document.getElementById('type').value = data.user.type;
            document.getElementById('password').required = false;
            document.getElementById('form_title').textContent = 'Редактировать пользователя';
            document.getElementById('submit_button').textContent = 'Сохранить изменения';
            document.getElementById('cancel_edit').style.display = 'inline-block';
            window.scrollTo(0, document.getElementById('user_form').offsetTop);
        } else {
            alert('Ошибка при загрузке данных пользователя');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при загрузке данных');
    });
}

document.getElementById('cancel_edit').addEventListener('click', function() {
    document.getElementById('user_form').reset();
    document.getElementById('user_id').value = '';
    document.getElementById('password').required = true;
    document.getElementById('form_title').textContent = 'Добавить пользователя';
    document.getElementById('submit_button').textContent = 'Добавить пользователя';
    document.getElementById('cancel_edit').style.display = 'none';
});

document.getElementById('user_form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('admin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Ошибка при сохранении пользователя');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при сохранении данных');
    });
});
</script>
</body>
</html>