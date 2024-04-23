<?php

session_start(); // Начинаем сессию для работы с сессионными переменными

if(empty($_COOKIE['id_user'])) {
    header("Location: ../login.php");
}

require_once("../db/db.php"); // Подключаем файл с настройками базы данных

var_dump($_POST);

$id_medication = $_POST['id_medication'];
$id_reason = $_POST['id_reason'];

mysqli_query($connect, "INSERT INTO `deleted_medications`
                        (`id_medication`, `id_reason`)
                        VALUES
                        ('$id_medication', '$id_reason')
");

header("Location: ../index.php");
