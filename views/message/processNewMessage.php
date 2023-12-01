<?php
session_start();

$_SESSION['prev'] = "processNewMessage";

include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

if (isset($_POST['senderID']) &&
    isset($_POST['recipient']) &&
    isset($_POST['message']) ) 
{
    $senderID = $_POST['senderID'];
    $recipient = $_POST['recipient'];
    $message = $_POST['message'];

    $recipientID = getUserID($recipient);

    // Check if recipient exists


    $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $sql=   "INSERT INTO messages(sender, recipient, message)" .
            "VALUES (" 
            . "'" . $senderID . "',"
            . "'" . $recipientID . "',"
            . "'" . $message . "'"
            . ")";
    
    if ($db->query($sql) === TRUE) {
        $_SESSION['sucess_message'] = 'Sėkmingai išsiųsta žinutė!<br>';
        header("Location: main.php");
        exit;
    } else {
        $_SESSION['error_message'] = 'Netikėta sistemos klaida. Pabandykite iš naujo.<br>' . $db->error . '<br>';
        header("Location: main.php");
        exit;
    }

} else {
    if(!empty($_POST)) {
        echo "POST data:<br>";
        foreach ($_POST as $key => $value) {
            echo "$key: $value<br>";
        }
    } else {
        echo "No data found in the POST.";
    }

    if (!empty($_SESSION)) {
        echo "SESSION data:<br>";
        foreach ($_SESSION as $key => $value) {
            echo "$key: $value<br>";
        }
    } else {
        echo "No data found in the session.";
    }
}

function getUserID($username) {
    $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $query = "SELECT UserID FROM users WHERE username = '$username'";
    $result = mysqli_query($db, $query);
    if (!$result) {
        die('Klaida: ' . mysqli_error($db));
    }

    if (mysqli_num_rows($result) == 0) {
        $_SESSION['error_message'] = "Šis vartotojas neegzistuoja.";
        header("Location: main.php");
        exit;
    }

    $row = mysqli_fetch_assoc($result);
    return $row['UserID'];
}

?>