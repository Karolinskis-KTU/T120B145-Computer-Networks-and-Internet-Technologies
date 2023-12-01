<?php
function getMessages($userID) {
    $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $query = "  SELECT * 
                FROM messages 
                WHERE 
                    recipient = '$userID' OR 
                    sender = '$userID'
                ORDER BY timestamp DESC";
    $result = mysqli_query($db, $query);
    if (!$result) {
        die('Klaida: ' . mysqli_error($db));
    }
    return $result;
}

function convertToUsername($userID) {
    $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $query = "SELECT username FROM users WHERE UserID = '$userID'";
    $result = mysqli_query($db, $query);
    if (!$result) {
        die('Klaida: ' . mysqli_error($db));
    }
    $row = mysqli_fetch_assoc($result);
    return $row['username'];
}
?>