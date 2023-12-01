<?php
session_start();

include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/functions.php");

if (!isset($_POST['password'])) {
    $errorMessage = "Nenurodytas slaptažodis";
    $_SESSION['error_message'] = $errorMessage;
    header("Location: /index.php");
    exit;
}

$password = $_POST['password'];
$isPasswordValid = checkpass($password, substr(hash('sha256', $password), 5, 32));
if(!$isPasswordValid) {
    $errorMessage = "Slaptažodis neatitinka reikalavimų";
    $_SESSION['error_message'] = $errorMessage;
    header("Location: /index.php");
    exit;
}

$dbpass = substr(hash('sha256', $password), 5, 32);
$db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
$sql = "UPDATE " . TBL_USERS . " 
        SET password='$dbpass' 
        WHERE username='" . $_SESSION['user'] . "'";

$result = mysqli_query($db, $sql);

if ($result) {
    $successMessage = "Slaptažodis sėkmingai pakeistas";
    $_SESSION['success_message'] = $successMessage;
    header("Location: /index.php");
    exit;
} else {
    $errorMessage = "Slaptažodžio pakeisti nepavyko." . mysqli_error($db);
    $_SESSION['error_message'] = $errorMessage;
    header("Location: /index.php");
    exit;
}