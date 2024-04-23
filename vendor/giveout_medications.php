<?php
session_start(); // Начинаем сессию для работы с сессионными переменными

require_once("../db/db.php"); // Подключаем файл с настройками базы данных

// Получаем id заявки из GET-запроса
$id_request = $_GET['id_request'];

// Выполняем запрос к базе данных для получения информации о заявке
$query_request = mysqli_query($connect, "SELECT * FROM `requests` WHERE `id` = '$id_request'");
$request_data = mysqli_fetch_assoc($query_request);

// Проверяем, что данные о заявке получены
if ($request_data) {
    // Разбиваем строку с id препаратов и строку с соответствующими количествами на отдельные значения
    $medication_ids = explode(', ', $request_data['ids_medical']);
    $quantities = explode(', ', $request_data['quantityes']);

    // Обрабатываем каждый препарат из заявки
    foreach (array_combine($medication_ids, $quantities) as $medication_id => $quantity) {
        // Получаем информацию о препарате из таблицы medications
        $query_medication = mysqli_query($connect, "SELECT * FROM `medications` WHERE `id` = '$medication_id'");
        $medication_data = mysqli_fetch_assoc($query_medication);

        // Если информация о препарате получена
        if ($medication_data) {
            // Разбиваем строку с количествами препарата на разных складах
            $quantities_on_warehouses = explode(', ', $medication_data['quantity_medication']);

            // Обновляем количество препарата на каждом складе
            foreach ($quantities_on_warehouses as &$quantity_on_warehouse) {
                // Вычитаем запрошенное количество из общего количества препарата на складе
                $quantity_on_warehouse -= $quantity;
            }
            unset($quantity_on_warehouse); // Удаляем ссылку на последний элемент

            // Находим максимальное значение
            $max_quantity = max($quantities_on_warehouses);

            // Обновляем запись в таблице medications только с максимальным количеством
            $new_quantities = array_fill(0, count($quantities_on_warehouses), $max_quantity);
            $new_quantities_str = implode(', ', $new_quantities);
            mysqli_query($connect, "UPDATE `medications` SET `quantity_medication` = '$new_quantities_str' WHERE `id` = '$medication_id'");

            // Устанавливаем статус заявки на выполненный
            mysqli_query($connect, "UPDATE `requests` SET `status` = 1 WHERE `id` = '$id_request'");
        }
    }

    // Перенаправляем пользователя на главную страницу
    header("Location: ../index.php");
} else {
    // Если данные о заявке не найдены, выводим сообщение об ошибке или выполняем другое действие
    echo "Данные о заявке не найдены.";
}
