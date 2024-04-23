<?php

session_start(); // Начинаем сессию для работы с сессионными переменными

if(empty($_COOKIE['id_user'])) {
    header("Location: ./login.php");
}

require_once("../db/db.php"); // Подключаем файл с настройками базы данных

$name_medication = $_POST['name_medication'];
$expiration_date = $_POST['expiration_date'];

// Создаем переменную для хранения id_warehouse_
$id_warehouse_string = '';

// Переменная для подсчета количества id_warehouse_
$id_warehouse_count = 0;

// Проходимся по массиву $_POST
foreach ($_POST as $key => $value) {
    // Проверяем, является ли ключ id_warehouse_
    if (strpos($key, 'id_warehouse_') === 0) {
        // Если это первый id_warehouse_, записываем его без запятой
        if ($id_warehouse_count == 0) {
            $id_warehouse_string .= $value;
        } else {
            // Если уже есть другие id_warehouse_, добавляем к строке с запятой
            $id_warehouse_string .= ', ' . $value;
        }
        // Увеличиваем счетчик id_warehouse_
        $id_warehouse_count++;
    }
}

$select_medication = mysqli_query($connect, "SELECT * FROM `medications` WHERE `name_medication`='$name_medication'");
$select_medication = mysqli_fetch_assoc($select_medication);

if(empty($select_medication)) {
    mysqli_query($connect, "INSERT INTO `medications`
                            (`id_warehouse`, `name_medication`, `expiration_date`)
                            VALUES
                            ('$id_warehouse_string', '$name_medication', '$expiration_date')
    ");
    header("Location: ../index.php");
} else {
    header("Location: ../index.php");
}
