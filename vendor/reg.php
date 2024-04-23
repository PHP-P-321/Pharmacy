<?php
session_start(); // Начинаем сессию для работы с сессионными переменными
require_once("../db/db.php"); // Подключаем файл с настройками базы данных

$fullname = $_POST['fullname']; // Получаем ФИО пользователя из формы
$email = $_POST['email']; // Получаем Email пользователя из формы
$login = $_POST['login']; // Получаем логин пользователя из формы
$password = $_POST['password']; // Получаем пароль пользователя из формы
$access_password = $_POST['access_password']; // Получаем подтверждение паролья пользователя из формы

if ($password == $access_password) {
    $select_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `email` = '$email'"); // Выполняем запрос к базе данных для выбора пользователя с указанным логином
    $select_user = mysqli_fetch_assoc($select_user); // Преобразуем результат запроса в ассоциативный массив

    if(!empty($select_user)) { // Проверяем, если результат запроса пустой (пользователь не найден)
        $_SESSION['errReg'] = "Пользователь с таким Email уже существует!"; // Устанавливаем сообщение об ошибке в сессионную переменную
        header("Location: ../reg.php"); // Перенаправляем пользователя на предыдущую страницу
    } else {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT); 
        mysqli_query($connect, "INSERT INTO `users`
                                (`role`, `login`, `email`, `fullname`, `password`)
                                VALUES
                                (2, '$login', '$email', '$fullname', '$pass_hash')");
        header("Location: ../login.php");
    }
} else {
    $_SESSION['errReg'] = "Введенные пароли не совпадают!"; // Устанавливаем сообщение об ошибке в сессионную переменную
    header("Location: ../reg.php"); // Перенаправляем пользователя на предыдущую страницу
}