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
$id_medication = $_POST['id_medication'];
$id_warehouse_from = $_POST['id_warehouse_from'];
$id_warehouse_to = $_POST['id_warehouse_to'];
$quantity = $_POST['quantity'];

// Запрос в базу данных для получения информации о препарате по его id
$query_medication = mysqli_query($connect, "SELECT * FROM `medications` WHERE `id` = '$id_medication'");
$medication_data = mysqli_fetch_assoc($query_medication);

// Проверяем, удалось ли получить информацию о препарате
if ($medication_data) {
    // Разбиваем строку с id складов и количеством препарата на складах на отдельные значения
    $warehouse_ids = explode(', ', $medication_data['id_warehouse']);
    $quantities = explode(', ', $medication_data['quantity_medication']);

    // Проверяем, есть ли склад $id_warehouse_to в списке складов препарата
    if (!in_array($id_warehouse_to, $warehouse_ids)) {
        // Если склада нет, добавляем его в список складов и устанавливаем количество препарата на нем равным 0
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

    // Обновляем запись в базе данных с новыми значениями складов и количества препарата на них
    $new_warehouse_ids_str = implode(', ', $warehouse_ids);
    $new_quantities_str = implode(', ', $quantities);
    mysqli_query($connect, "UPDATE `medications` SET `id_warehouse` = '$new_warehouse_ids_str', `quantity_medication` = '$new_quantities_str' WHERE `id` = '$id_medication'");

    // Перенаправляем пользователя на главную страницу
    header("Location: ../index.php");
} else {
    // Если данные о препарате не найдены, перенаправляем пользователя на главную страницу с сообщением об ошибке или другими действиями
    header("Location: ../index.php");
}
