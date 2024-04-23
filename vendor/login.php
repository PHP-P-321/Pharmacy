<?php
session_start(); // Начинаем сессию для работы с сессионными переменными
require_once("../db/db.php"); // Подключаем файл с настройками базы данных

$login = $_POST['login']; // Получаем логин пользователя из формы
$password = $_POST['password']; // Получаем пароль пользователя из формы

$select_user = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$login'"); // Выполняем запрос к базе данных для выбора пользователя с указанным логином
$select_user = mysqli_fetch_assoc($select_user); // Преобразуем результат запроса в ассоциативный массив

if(empty($select_user)) { // Проверяем, если результат запроса пустой (пользователь не найден)
    header("Location: " . $_SERVER['HTTP_REFERER']); // Перенаправляем пользователя на предыдущую страницу
} else {
    if(password_verify($password, $select_user['password'])) { // Проверяем правильность введенного пароля с помощью функции password_verify
        setcookie("id_user", $select_user['id'], time()+28800, "/"); // Устанавливаем куку с ID пользователя
        setcookie("role", $select_user['role'], time()+28800, "/"); // Устанавливаем куку с ролью пользователя
        header("Location: ../index.php"); // Перенаправляем пользователя на главную страницу
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']); // Перенаправляем пользователя на предыдущую страницу
    }
}
