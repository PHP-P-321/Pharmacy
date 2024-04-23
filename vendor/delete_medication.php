<?php
session_start(); // Начинаем сессию для работы с сессионными переменными

// Проверяем, авторизован ли пользователь, используя куки
if(empty($_COOKIE['id_user'])) {
    // Если пользователь не авторизован, перенаправляем его на страницу входа
    header("Location: ../login.php");
}

// Подключаем файл с настройками базы данных
require_once("../db/db.php");

// Выводим данные из POST-запроса (используется только для отладки)
var_dump($_POST);

// Получаем данные из POST-запроса
$id_medication = $_POST['id_medication'];
$id_reason = $_POST['id_reason'];

// Выполняем запрос к базе данных для добавления информации о причине удаления препарата
mysqli_query($connect, "INSERT INTO `deleted_medications`
                        (`id_medication`, `id_reason`)
                        VALUES
                        ('$id_medication', '$id_reason')
");

// Перенаправляем пользователя на главную страницу
header("Location: ../index.php");
