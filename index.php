<?php

session_start();

if(empty($_COOKIE['id_user'])) {
    header("Location: ./login.php");
}

require_once("./db/db.php");

$select_warehouses = mysqli_query($connect, "SELECT `id`, `name_warehouse` FROM `warehouses`");
$select_warehouses = mysqli_fetch_all($select_warehouses);

$select_not_deleted_medications = mysqli_query($connect, "SELECT * FROM `medications` WHERE `id` NOT IN (SELECT `id_medication` FROM `deleted_medications`);");
$select_not_deleted_medications = mysqli_fetch_all($select_not_deleted_medications);

$select_reasons = mysqli_query($connect, "SELECT * FROM `reasons`");
$select_reasons = mysqli_fetch_all($select_reasons);

$select_requests = mysqli_query($connect, "SELECT * FROM `requests` WHERE `status` != 1");
$select_requests = mysqli_fetch_all($select_requests);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <link rel="stylesheet" href="./assets/style/style.css">
</head>
<body>
    <a href="./logout.php">Выйти</a>

    <?php if($_COOKIE['role'] == 1) { ?>
        <div class="wrap">
            <div class="left">
                <h2>Принять лекарство</h2>
                <form action="./vendor/create-medication.php" method="post">
                    <input type="text" name="name_medication" placeholder="Название препарата" required>
                    <input type="text" name="quantity_medication" placeholder="Количество препарата (указать через , согласно складам )" required>
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
                <form action="./vendor/delete_medication.php" method="post" id="deleteForm">
                    <select name="id_medication" required>
                        <?php foreach($select_not_deleted_medications as $medication) { ?>
                            <option value="<?= $medication[0] ?>"><?= $medication[2] ?></option>
                        <?php } ?>
                    </select>
                    <select name="id_reason" required>
                        <?php foreach($select_reasons as $reason) { ?>
                            <option value="<?= $reason[0] ?>"><?= $reason[1] ?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" value="Списать">
                </form>
            </div>
        </div>
        <div class="wrap">
            <div class="left">
                <h2>Список заявок на выдачу препаратов для отделений</h2>
                <div class="requests">
                    <?php foreach($select_requests as $request) { ?>
                        <div class="request<?= ($request[3] == 1) ? ' red' : '' ?>">
                            <ul>
                                <?php
                                // Разбиваем строку с id препаратов на отдельные id
                                $medication_ids = explode(', ', $request[1]);

                                // Разбиваем строку с количествами препаратов на отдельные значения
                                $quantities = explode(', ', $request[2]);

                                // Выполняем запрос в таблицу medications для каждого id препарата
                                foreach (array_combine($medication_ids, $quantities) as $medication_id => $quantity) {
                                    $query_medication = mysqli_query($connect, "SELECT * FROM `medications` WHERE `id` = '$medication_id'");
                                    $medication_data = mysqli_fetch_assoc($query_medication);

                                    // Выводим информацию о препарате, если она получена
                                    if ($medication_data) {?>
                                        <li>
                                            Препарат - <?= $medication_data['name_medication'] ?> | Количество - <?= $quantity ?>
                                        </li>
                                    <?php }
                                }
                                ?>
                            </ul>
                            <a href="./vendor/giveout_medications.php?id_request=<?= $request[0] ?>">Выдать</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="right">
                <h2>Перемещение препаратов между складами</h2>
                <form action="./vendor/change-warehouses.php" method="post" id="changeWarehouseForm">
                    <select name="id_medication" id="medicationSelect" required>
                        <?php foreach($select_not_deleted_medications as $medication) { ?>
                            <option value="<?= $medication[0] ?>"><?= $medication[2] ?></option>
                        <?php } ?>
                    </select>
                    <div>
                        Со склада
                        <select name="id_warehouse_from" id="warehouseFromSelect" required>
                            <?php foreach($select_warehouses as $warehouse) { ?>
                                <option value="<?= $warehouse[0] ?>"><?= $warehouse[1] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div>
                        В склад
                        <select name="id_warehouse_to" id="warehouseToSelect" required>
                            <?php foreach($select_warehouses as $warehouse) { ?>
                                <option value="<?= $warehouse[0] ?>"><?= $warehouse[1] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <input type="text" name="quantity" id="quantityInput" placeholder="" required>
                    <input type="submit" value="Переместить">
                </form>
            </div>
        </div>
    <?php } elseif($_COOKIE['role'] == 2) { ?>

    <?php } ?>

    <script>
        // Передача данных через скриптовую переменную
        var medicationsData = <?php echo json_encode($select_not_deleted_medications); ?>;
    </script>
    <script src="./assets/script/main.js"></script>
</body>
</html>