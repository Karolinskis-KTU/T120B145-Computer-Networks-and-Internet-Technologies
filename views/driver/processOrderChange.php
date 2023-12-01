<?php
session_start();
$userID = $_SESSION['userid'];

include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

if(isset($_POST['end-order'])) {
    changeDriverMoneyCollected($userID);
    changeCarStatusAndTimeout($userID);
    changeOrderStatusToCompleted($userID);

    $_SESSION['success_message'] = "Užsakymas sėkmingai įvykdytas";
    header("Location: main.php");
    exit();
}

function changeDriverMoneyCollected($userID) {
    $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_error) {
        $_SESSION['error_message'] = "Nepavyko prisijungti prie serverio: " . $db->connect_error;
        header("Location: main.php");
        exit();
    }
    $sql = "UPDATE drivers 
            SET moneyCollected = moneyCollected + (
                SELECT price 
                FROM orders 
                WHERE DriverID = '$userID' 
                AND OrderStatus = 'Vykdoma'
            ),
            totalDistanceTraveled = totalDistanceTraveled + (
                SELECT distance 
                FROM orders 
                WHERE DriverID = '$userID' 
                AND OrderStatus = 'Vykdoma'
            ) 
            WHERE userID = '$userID'";
    $result = $db->query($sql);

    if($result) {
        echo "Vairuotojo informacija sėkmingai išsaugota<br>";
    } else {
        $_SESSION['error_message'] = "Ivyko klaida skaitant užsakymų duomenų bazę: " . $db->error;
        header("Location: main.php");
        exit();
    }
}

function changeOrderStatusToCompleted($userID) {
    // Change order status to "Įvykdyta"
    $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_error) {
        $_SESSION['error_message'] = "Nepavyko prisijungti prie serverio: " . $db->connect_error;
        header("Location: main.php");
        exit();
    }
    $sql = "UPDATE orders 
            SET OrderStatus = 'Įvykdyta' 
            WHERE DriverID = '$userID' 
            AND OrderStatus = 'Vykdoma'";
    $result = $db->query($sql);

    if($result) {
        echo "Užsakymo informacija sėkmingai išsaugota<br>";
    } else {
        $_SESSION['error_message'] = "Ivyko klaida skaitant užsakymų duomenų bazę: " . $db->error;
        header("Location: main.php");
        exit();
    }
}

function changeCarStatusAndTimeout($userID) {
    $carID = getCarID($userID);
    // Change car status to "Laukia" and add 5 minutes to Timestamp from now
    $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_error) {
        $_SESSION['error_message'] = "Nepavyko prisijungti prie serverio: " . $db->connect_error;
        header("Location: main.php");
        exit();
    }
    $sql = "UPDATE cars 
            SET status = 'Laukia', 
            Timestamp = DATE_ADD(NOW(), INTERVAL 5 MINUTE),
            Range_left = Range_Left - (
                SELECT distance 
                FROM orders 
                WHERE DriverID = '$userID' 
                AND OrderStatus = 'Vykdoma'
            )  
            WHERE id = '$carID'";
    $result = $db->query($sql);

    if($result) {
        echo "Automobilio informacija sėkmingai išsaugota<br>";
    } else {
        $_SESSION['error_message'] = "Ivyko klaida skaitant automobilių duomenų bazę: " . $db->error;
        header("Location: main.php");
        exit();
    }
}

function getCarID($userID) {
    $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    if ($db->connect_error) {
        $_SESSION['error_message'] = "Nepavyko prisijungti prie serverio: " . $db->connect_error;
        header("Location: main.php");
        exit();
    }
    $sql = "SELECT carID FROM drivers WHERE userid = '$userID'";
    $result = $db->query($sql);

    if($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['carID'];
        } else {
            $_SESSION['error_message'] = "Nepavyko vairuotojo";
            header("Location: main.php");
            exit();
        }
        $result->free_result();
    } else {
        // Error occured
        $_SESSION['error_message'] = "Ivyko klaida skaitant vairuotojų duomenų bazę: " . $db->error;
        header("Location: main.php");
        exit();
    }
}

function getOrderRange($userID) {

}

?>