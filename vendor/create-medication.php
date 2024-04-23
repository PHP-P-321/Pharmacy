<?php
session_start(); // Начинаем сессию для работы с сессионными переменными

// Проверяем, авторизован ли пользователь, используя куки
if(empty($_COOKIE['id_user'])) {
    // Если пользователь не авторизован, перенаправляем его на страницу входа
    header("Location: ../login.php");
}

// Подключаем файл с настройками базы данных
require_once("../db/db.php");

// Получаем данные из POST-запроса
$name_medication = $_POST['name_medication'];
$quantity_medication = $_POST['quantity_medication'];
$expiration_date = $_POST['expiration_date'];

// Создаем переменную для хранения id складов
$id_warehouse_string = '';

// Переменная для подсчета количества id складов
$id_warehouse_count = 0;

// Проходимся по массиву $_POST
foreach ($_POST as $key => $value) {
    // Проверяем, является ли ключ id_warehouse_
    if (strpos($key, 'id_warehouse_') === 0) {
        // Если это первый id склада, записываем его без запятой
        if ($id_warehouse_count == 0) {
            $id_warehouse_string .= $value;
        } else {
            // Если уже есть другие id складов, добавляем к строке с запятой
            $id_warehouse_string .= ', ' . $value;
        }
        // Увеличиваем счетчик id складов
        $id_warehouse_count++;
    }
}

// Выполняем запрос к базе данных для проверки, существует ли уже препарат с таким названием
$select_medication = mysqli_query($connect, "SELECT * FROM `medications` WHERE `name_medication`='$name_medication'");
$select_medication = mysqli_fetch_assoc($select_medication);

// Если препарат с таким названием не существует, добавляем его в базу данных
if(empty($select_medication)) {
    mysqli_query($connect, "INSERT INTO `medications`
                            (`id_warehouse`, `name_medication`, `quantity_medication`, `expiration_date`)
                            VALUES
                            ('$id_warehouse_string', '$name_medication', '$quantity_medication', '$expiration_date')
    ");
    // Перенаправляем пользователя на главную страницу
    header("Location: ../index.php");
} else {
    // Если препарат с таким названием уже существует, перенаправляем пользователя на главную страницу
    header("Location: ../index.php");
}
