<?php
session_start();
// If user is not logged in or is not a customer, redirect to main page
// if (!isset($_SESSION['ulevel']) || array_search($_SESSION['ulevel'], $user_roles) !== "Dispečeris") {
//     $_SESSION['error_message'] = "Norėdami peržiūrėti šį puslapį, turite būti prisijungęs kaip dispečeris.";
//     header("Location: /index.php");
//     exit();
// }
$_SESSION['prev'] = "editOrder";



include("../../include/config.php");

if (isset($_POST['auto']) && isset($_POST['order-status']) && isset($_POST['order-id'])) {
    // Check if order-status is in list
    $selected_status = $_POST['order-status'];
    // if (!in_array($selected_status, $car_statuses)) {
    //     $_SESSION['error_message'] = "Pasirinktas neteisingas užsakymo statusas";
    // }
    $autoID = $_POST['auto'];
    $orderID = $_POST['order-id'];

    // Get driver userid
    $db_driverID=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $sql_driverID= "SELECT userid FROM " . TBL_DRIVERS . " WHERE carID = " . $autoID;
    $stmt = $db_driverID->prepare($sql_driverID);
    if ($stmt->execute()) {
        // Bind the result variable
        $stmt->bind_result($DriverID);

        if (!($stmt->fetch())) {
            $_SESSION['error_message'] .= "Nerastas vairuotojas.<br>";
        }
    } else {
        $_SESSION['error_message'] .= "Klaida skaitant vairuotojus: " . $db_driverID->error;
        header("Location: orders.php");
        exit;
    }


    // 'order'
    // - Change driverID to assigned ID
    // - Change Orderstatus to "Vykdoma" 
    $db_order=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $sql_order=  "UPDATE " . TBL_ORDERS .
                " SET OrderStatus = 'Vykdoma', DriverID = '" . $DriverID . "'" .
                " WHERE id = " . $orderID;
    if ($db_order->query($sql_order) === TRUE) {
        echo "Užsakymas sėkmingai išsaugotas";
    } else {
        $_SESSION['error_message'] .= "Nepavyko išsaugoti užsakymo duomenų: " . $db_order->error . "<br>";
        header("Location: orders.php");
        exit;
    }

    // 'cars'
    // - Change status to "Dirba"
    $db_cars=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $sql_cars = "UPDATE " . TBL_CARS .
                " SET status='Dirba' " .
                " WHERE id=" . $autoID;
    if ($db_cars->query($sql_cars) === TRUE) {
        echo "Automobilis sėkmingai išsaugotas";
    } else {
        $_SESSION['error_message'] .= "Nepavyko išsaugoti automobilio duomenų: " . $db_cars->error . "<br>";
        header("Location: orders.php");
        exit;
    }

    $_SESSION['success_message'] = "Sėkmingai išsaugoti duomenys!";
    header("Location: orders.php");
    exit;
        
    
} else {
    if(!isset($_SESSION['error_message'])) {
        $_SESSION['error_message'] = '';
    }

    if (!isset($_POST['auto'])) {
        $_SESSION['error_message'] .= 'Nepasirinktas automobilis.<br>';
    }
    if (!isset($_POST['order-status'])) {
        $_SESSION['error_message'] .= 'Nepasirinktas užsakymo statusas.<br>';
    }
    if (!isset($_POST['order-id'])) {
        $_SESSION['error_message'] .= 'Neteisingas užsakymas.<br>';
    }
    header("Location: orders.php?error");
    exit;
}
?>