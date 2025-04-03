<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Отель - Админка</title>
    <link rel="stylesheet" href="style/admin.css">  
</head>
<body>
<header class="header">
        <div class="container">
            <p class="header_admin">Админ_имя</p>
            <a class="header_login" href="?do=logout">Выйти <i class="fa fa-sign-out" aria-hidden="true"></i>
            </a>
        </div>
    </header>
    <section class="clients">
    <h2 class="clients_title">Список пользователей</h2>  
    <div class="clients_list">
        <table>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Телефон</th>
                <th>Редактировать</th>
            </tr>
            <tr> 
                <td>Иван Иванов</td>
                <td>ivan@gmail.com</td>
                <td>+79999999999</td>
                <td><a href="">Редактировать</a></td>
            </tr>   
        </table>
            </div>
        </div>
    </div>
</section>
<section class="clients">
    <h2 class="clients_title">Добавить пользователя</h2>  
    <div class="client_add">
        <div class="client_add_form">
        <label for="login">Фамилия:</label> 
        <input type="text" name="login" placeholder="фамилия" >

        <label for="password">Имя:</label> 
        <input type="password" name="password" placeholder="имя" >

        <label for="password">Отчество:</label> 
        <input type="password" name="password" placeholder="отчество" >

        <label for="password">Email: <span class="required">*</span></label> 
        <input type="password" name="password" placeholder="email" required>

        <label for="password">Телефон: <span class="required">*</span></label> 
        <input type="password" name="password" placeholder="телефон" required>
        
        <label for="password">Логин : <span class="required">*</span></label> 
        <input type="password" name="password" placeholder="логин" required>

        <label for="password">Пароль: <span class="required">*</span></label> 
        <input type="password" name="password" placeholder="пароль" required>
            </div>
            <button class="add_client">Добавить пользователя</button>
        </div>
    </div>
</section>

</body>
</html>