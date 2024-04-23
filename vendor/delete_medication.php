<?php

session_start(); // Начинаем сессию для работы с сессионными переменными

if(empty($_COOKIE['id_user'])) {
    header("Location: ./login.php");
}

require_once("../db/db.php"); // Подключаем файл с настройками базы данных

var_dump($_POST);
