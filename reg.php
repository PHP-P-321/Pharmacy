<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 300px;
        }
    </style>
</head>
<body>
    <h2>Авторизация</h2>
    <form action="./vendor/reg.php" method="post">
        <input type="text" name="fullname" placeholder="ФИО" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="login" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <input type="password" name="access_password" placeholder="Подтверждение Пароля" required>
        <input type="submit" value="Зарегистрироваться">
    </form>
    <br>
    <a href="./login.php">Авторизоваться</a>
</body>
</html>