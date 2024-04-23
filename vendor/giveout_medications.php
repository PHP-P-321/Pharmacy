<?php
session_start();
require_once("../db/db.php");

$id_request = $_GET['id_request'];

// Получаем информацию о заявке
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
            // Копируем массив, чтобы сохранить исходные значения
            $original_quantities_on_warehouses = $quantities_on_warehouses;

            // Получаем список id складов для данного препарата
            $warehouse_ids = explode(', ', $medication_data['id_warehouse']);

            // Обновляем количество препарата на каждом складе
            for ($i = 0; $i < count($warehouse_ids); $i++) {
                // Вычитаем запрошенное количество из общего количества препарата на складе
                $quantities_on_warehouses[$i] -= $quantity;
            }

            // Находим максимальное значение
            $max_quantity = max($quantities_on_warehouses);

            // Обновляем запись в таблице medications только с максимальным количеством
            $new_quantities = [];
            for ($i = 0; $i < count($warehouse_ids); $i++) {
                if ($quantities_on_warehouses[$i] === $max_quantity) {
                    $new_quantities[] = $max_quantity;
                } else {
                    // Восстанавливаем исходное значение для склада, который не изменился
                    $new_quantities[] = $original_quantities_on_warehouses[$i];
                }
            }
            $new_quantities_str = implode(', ', $new_quantities);
            mysqli_query($connect, "UPDATE `medications` SET `quantity_medication` = '$new_quantities_str' WHERE `id` = '$medication_id'");

            mysqli_query($connect, "UPDATE `requests` SET `status` = 1 WHERE `id` = '$id_request'");
        }
    }

    // Перенаправляем пользователя на главную страницу
    header("Location: ../index.php");
} else {
    // Если данные о заявке не найдены, выводим сообщение об ошибке или выполняем другое действие
    echo "Данные о заявке не найдены.";
}
