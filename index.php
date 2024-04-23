<?php

session_start();

if(empty($_COOKIE['id_user'])) {
    header("Location: ./login.php");
}

require_once("./db/db.php");

$select_warehouses = mysqli_query($connect, "SELECT `id`, `name_warehouse` FROM `warehouses`");
$select_warehouses = mysqli_fetch_all($select_warehouses);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 300px;
        }
        form p {
            margin: 0;
        }
    </style>
</head>
<body>
    <a href="./logout.php">Выйти</a>

    <?php if($_COOKIE['role'] == 1) { ?>
        <div class="left">
            <h2>Принять лекарство</h2>
            <form action="./vendor/create-medication.php" method="post">
                <input type="text" name="name_medication" placeholder="Название препарата" required>
                <ul>
                    <?php foreach($select_warehouses as $warehouse) { ?>
                        <li>
                            <div style="display: flex;">
                                <input type="checkbox" name="id_warehouse_<?= $warehouse[0] ?>" value="<?= $warehouse[0] ?>">
                                <p><?= $warehouse[1] ?></p>
                            </div>
                        </li>
                    <?php } ?>
                </ul>
                <p>Срок годности (дата списания)</p>
                <input type="date" name="expiration_date" required>
                <input type="submit" value="Принять">
            </form>
        </div>
        <div class="right">
            <h2>Списать лекарство</h2>
            <form action="./vendor/delete_medication.php" method="post">

            <input type="submit" value="Списать">
            </form>
        </div>
    <?php } elseif($_COOKIE['role'] == 2) { ?>

    <?php } ?>
</body>
</html>