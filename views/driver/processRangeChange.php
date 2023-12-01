<?php
session_start();
$userID = $_SESSION['userid'];

include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

if(isset($_POST['change-range'])) {
    if(isset($_POST['range'])) {
        $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            $_SESSION['error_message'] = "Nepavyko prisijungti prie serverio: " . $db->connect_error;
            header("Location: main.php");
            exit();
        }
        $userID = $_SESSION['userid'];
        $newRange = $_POST['range'];
        $sql = "UPDATE cars
                INNER JOIN drivers ON cars.id = drivers.carID
                SET cars.Range_left = '$newRange'
                WHERE drivers.userID = '$userID'";
        if (mysqli_query($db, $sql)) {
            $_SESSION['success_message'] = "Atstumas sėkmingai pakeistas";
            header("Location: main.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Klaida keičiant atstumą: " . mysqli_error($db);
            header("Location: main.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Nepasirinktas atstumas";
        header("Location: main.php");
        exit();
    }

}