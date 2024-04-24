<?php
session_start(); // Начинаем сессию для работы с сессионными переменными

// Проверяем, авторизован ли пользователь, используя куки
if(empty($_COOKIE['id_user'])) {
    // Если пользователь не авторизован, отправляем пустой ответ
    echo json_encode(array('error' => 'Пользователь не авторизован'));
    exit; // Завершаем выполнение скрипта
}

// Подключаем файл с настройками базы данных
require_once("../db/db.php");

// Проверяем наличие поискового запроса
if(isset($_POST['search'])) {
    $searchQuery = $_POST['search'];

    // Выполняем запрос к базе данных для поиска препаратов
    $query = "SELECT * FROM `medications` WHERE `id` NOT IN (SELECT `id_medication` FROM `deleted_medications`) AND `name_medication` LIKE '%$searchQuery%'";
    $result = mysqli_query($connect, $query);

    // Проверяем, есть ли результаты
    if(mysqli_num_rows($result) > 0) {
        // Преобразуем результат в ассоциативный массив
        $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Преобразуем массив в формат JSON и отправляем обратно клиенту
        echo json_encode($data);
    } else {
        // Если результатов нет, отправляем пустой массив
        echo json_encode([]);
    }
} else {
    // Если поисковый запрос отсутствует, отправляем сообщение об ошибке
    echo "Ошибка: поисковый запрос не найден.";
}
