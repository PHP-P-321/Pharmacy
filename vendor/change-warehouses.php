<?php

session_start(); // Начинаем сессию для работы с сессионными переменными

if(empty($_COOKIE['id_user'])) {
    header("Location: ../login.php");
}

require_once("../db/db.php"); // Подключаем файл с настройками базы данных

var_dump($_POST);

$id_medication = $_POST['id_medication'];
$id_warehouse_from = $_POST['id_warehouse_from'];
$id_warehouse_to = $_POST['id_warehouse_to'];
$quantity = $_POST['quantity'];

$query_medication = mysqli_query($connect, "SELECT * FROM `medications` WHERE `id` = '$id_medication'");
$medication_data = mysqli_fetch_assoc($query_medication);

// Проверяем, что данные препарата получены
if ($medication_data) {
    // Разбиваем строку с id складов и количеством препарата на складах
    $warehouse_ids = explode(', ', $medication_data['id_warehouse']);
    $quantities = explode(', ', $medication_data['quantity_medication']);

    // Проверяем, есть ли склад $id_warehouse_to в списке складов
    if (!in_array($id_warehouse_to, $warehouse_ids)) {
        // Добавляем новый склад и количество 0 в соответствующие массивы
        $warehouse_ids[] = $id_warehouse_to;
        $quantities[] = 0;
    }

    // Находим индекс склада $id_warehouse_from и вычитаем из его количества $quantity
    $index_from = array_search($id_warehouse_from, $warehouse_ids);
    if ($index_from !== false) {
        $quantities[$index_from] -= $quantity;
    }

    // Находим индекс склада $id_warehouse_to и прибавляем к его количеству $quantity
    $index_to = array_search($id_warehouse_to, $warehouse_ids);
    if ($index_to !== false) {
        $quantities[$index_to] += $quantity;
    }

    // Обновляем запись в базе данных
    $new_warehouse_ids_str = implode(', ', $warehouse_ids);
    $new_quantities_str = implode(', ', $quantities);
    mysqli_query($connect, "UPDATE `medications` SET `id_warehouse` = '$new_warehouse_ids_str', `quantity_medication` = '$new_quantities_str' WHERE `id` = '$id_medication'");

    // Перенаправляем пользователя на страницу index.php
    header("Location: ../index.php");
} else {
    // В случае, если данные о препарате не найдены, можно выполнить какое-то другое действие, например, вывести ошибку
    echo "Данные о препарате не найдены.";
}

header("Location: ../index.php");
