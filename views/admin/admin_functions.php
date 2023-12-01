<?php
    function getUsers() {
        $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            die("Nepavyko prisijungti prie serverio: " . $db->connect_error);
        }
        $sql = "SELECT * FROM users";
        $result = $db->query($sql);
        if ($result) {
            return $result;
        } else {
            echo "Klaida: " . $db->error;
            return null;
        }
    }
?>