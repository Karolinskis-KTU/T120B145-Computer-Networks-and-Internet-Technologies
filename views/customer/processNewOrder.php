<?php
session_start();

$_SESSION['prev'] = "processNewOrder";

include("../../include/config.php");

if (isset($_POST['from']) &&
    isset($_POST['to']) &&
    isset($_POST['distance']) &&
    isset($_POST['price']) &&
    isset($_POST['start_latitude']) &&
    isset($_POST['start_longitude']) &&
    isset($_POST['end_latitude']) &&
    isset($_POST['end_longitude']) ) 
{
    $from = $_POST['from'];
    $to = $_POST['to'];
    $distance = $_POST['distance'];
    $price = $_POST['price'];
    $start_latitude = $_POST['start_latitude'];
    $start_longitude = $_POST['start_longitude'];
    $end_latitude = $_POST['end_latitude'];
    $end_longitude = $_POST['end_longitude'];

    $start_coords = $start_latitude . ',' . $start_longitude;
    $end_coords = $end_latitude . ',' . $end_longitude;

    $userid = $_SESSION['userid'];

    $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $sql=   "INSERT INTO orders(CustomerID, PickupAddress, DestinationAddress, PickupCoordinates, DestinationCoordinates, Distance, Price, OrderStatus)" .
            "VALUES (" 
            . "'" . $userid . "',"
            . "'" . $from . "',"
            . "'" . $to . "',"
            . "'" . $start_coords . "',"
            . "'" . $end_coords . "',"
            . "'" . $distance . "',"
            . " " . $price . ","
            . "'" . "Laukiama" . "'"
            . ")";
    
    if ($db->query($sql) === TRUE) {
        $_SESSION['sucess_message'] .= 'Sėkmingai sukurtas užsakymas!<br>';
        header("Location: main.php");
        exit;
    } else {
        $_SESSION['error_message'] .= 'Netikėta sistemos klaida. Pabandykite iš naujo.<br>' . $db->error . '<br>';
        header("Location: newOrder.php?error");
        exit;
    }

} else {
    if (!empty($_SESSION)) {
        foreach ($_SESSION as $key => $value) {
            echo "$key: $value<br>";
        }
    } else {
        echo "No data found in the session.";
    }

    if (!empty($_POST)) {
        foreach ($_POST as $key => $value) {
            echo "$key: $value<br>";
        }
    } else {
        echo "No data found in POST.";
    }
    

    if(!isset($_SESSION['error_message'])) {
        $_SESSION['error_message'] = '';
    }

    if (!isset($_POST['from'])) {
        $_SESSION['error_message'] .= 'Neįvesta kelionės pradžia.<br>';
    }
    if (!isset($_POST['to'])) {
        $_SESSION['error_message'] .= 'Neįvesta kelionės pabaiga.<br>';
    }
    //header("Location: newOrder.php?errror&from&to");
    exit;
    if (!isset($_POST['distance']) &&
        !isset($_POST['price']) &&
        !isset($_POST['start_latitude']) &&
        !isset($_POST['start_longitude']) &&
        !isset($_POST['end_latitude']) &&
        !isset($_POST['end_longitude']) ) 
    {
        $_SESSION['error_message'] .= 'Netikėta sistemos klaida. Pabandykite iš naujo.<br>';
    } else {
        $_SESSION['error_message'] .= 'Kritinė sistemos klaida. Susisiekite su administratoriu.<br>';
    }
    header("Location: newOrder.php?errror");
    exit;
}