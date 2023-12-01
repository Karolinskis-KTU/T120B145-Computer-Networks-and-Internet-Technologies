<?php
session_start();
if (isset($_GET['userid'])) {
    $user_id = $_GET['userid'];
} else {
    $errorMessage = "Nenurodytas vartotojas";
    $_SESSION['error_message'] = $errorMessage;
    header("Location: accountList.php");
    exit;
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $errorMessage = "Nenurodyta veiksmo rūšis";
    $_SESSION['error_message'] = $errorMessage;
    header("Location: accountList.php");
    exit;
}

// If user is not logged in or is not admin, redirect to main page
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
if (!isset($_SESSION['ulevel']) || array_search($_SESSION['ulevel'], $user_roles) !== "Administratorius") {
    $_SESSION['error_message'] = "Norėdami peržiūrėti šį puslapį, turite būti prisijungęs kaip administratorius.";
    header("Location: /index.php");
    exit();
}

$_SESSION['prev'] = "editUser";

if($action == "block") {
    blockUser($user_id, $user_roles[BLOCKED_LEVEL]);
    $successMessage = "Vartotojas sėkmingai užblokuotas";
    $_SESSION['success_message'] = $successMessage;
    header("Location: accountList.php");
    exit;
} else if ($action == "unblock") {
    unblockUser($user_id, $user_roles[DEFAULT_LEVEL]);
    $successMessage = "Vartotojas sėkmingai atblokuotas";
    $_SESSION['success_message'] = $successMessage;
    header("Location: accountList.php");
    exit;
} else if ($action == "delete") {
    deleteUser($user_id);
    $successMessage = "Vartotojas sėkmingai ištrintas";
    $_SESSION['success_message'] = $successMessage;
    header("Location: accountList.php");
} else {
    $errorMessage = "Nenurodyta veiksmo rūšis";
    $_SESSION['error_message'] = $errorMessage;
    header("Location: accountList.php");
    exit;
}


function blockUser($userID, $ulevel) {
    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $sql = "UPDATE " . TBL_USERS . " 
            SET userlevel = " . $ulevel . " 
            WHERE userid = \"" . $userID . "\"";
    if (!mysqli_query($db, $sql)) {
        die(" DB klaida įrašant vartotojo duomenis: " . $sql . "<br>" . mysqli_error($db));
        exit;
    }
    mysqli_close($db);
}

function unblockUser($userID, $ulevel) {
    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $sql = "UPDATE " . TBL_USERS . " 
            SET userlevel = " . $ulevel . " 
            WHERE userid = \"" . $userID . "\"";
    if (!mysqli_query($db, $sql)) {
        die(" DB klaida įrašant vartotojo duomenis: " . $sql . "<br>" . mysqli_error($db));
        exit;
    }
    mysqli_close($db);
}

function deleteUser($userID) {
    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $sql = "DELETE FROM " . TBL_USERS . " 
            WHERE userid = \"" . $userID . "\"";
    if (!mysqli_query($db, $sql)) {
        die(" DB klaida įrašant vartotojo duomenis: " . $sql . "<br>" . mysqli_error($db));
        exit;
    }
    mysqli_close($db);
}
?>

