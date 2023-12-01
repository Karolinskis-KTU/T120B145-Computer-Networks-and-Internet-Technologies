<?php
session_start();
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/functions.php");

if (!isset($_SESSION['ulevel']) || array_search($_SESSION['ulevel'], $user_roles) !== "Administratorius") {
    $_SESSION['error_message'] = "Norėdami peržiūrėti šį puslapį, turite būti prisijungęs kaip administratorius.";
    header("Location: /index.php");
    exit();
}

if (isset($_POST['user']) && isset($_POST['email']) && isset($_POST['role'])) {
    $user = $_POST['user'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $userid = md5(uniqid($user));

    if(!checkname($user)) {
        $errorMessage = "Neteisingas vartotojo vardas";
        $_SESSION['error_message'] = $errorMessage;
        header("Location: createUser.php");
        exit;
    }

    List($dbuname) = checkdb($user);
    if($dbuname) {
        $errorMessage = "Toks vartotojas jau egzistuoja";
        $_SESSION['error_message'] = $errorMessage;
        header("Location: createUser.php");
        exit;
    }

    // $password = generatePassword();
    // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $sql = "INSERT INTO " . TBL_USERS . " (userid, username, email, userlevel) VALUES (\"" . $userid . "\",  \"" . $user . "\", \"" . $email . "\", \"" . $role . "\")";
    $result = mysqli_query($db, $sql);
    if ($result) {
        $successMessage = "Vartotojas sėkmingai sukurtas";
        $_SESSION['success_message'] = $successMessage;
        header("Location: accountList.php");
        exit;
    } else {
        $errorMessage = "Vartotojo sukurti nepavyko." . mysqli_error($db);
        $_SESSION['error_message'] = $errorMessage;
        header("Location: accountList.php");
        exit;
    }
} else {
    $errorMessage = "Nenurodyti visi privalomi laukai";
    $_SESSION['error_message'] = $errorMessage;
    header("Location: createUser.php");
    exit;
}
?>