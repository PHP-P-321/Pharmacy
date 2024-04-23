<?php
setcookie("id_user", null, -1, "/");
setcookie("role", null, -1, "/");

// перенаправляем на главную страницу
header("Location: ./index.php");