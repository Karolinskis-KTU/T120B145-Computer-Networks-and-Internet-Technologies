<?php
function getOrders() {
    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if (!$db) {
        die("Nepavyko prisijungti prie serverio: " . mysqli_connect_error());
    }

    $sql = "SELECT 
                o.id,
                c.username AS CustomerName, 
                d.username AS DriverName,
                o.PickupAddress,
                o.DestinationAddress,
                o.PickupCoordinates,
                o.DestinationCoordinates,
                o.Distance,
                o.Price,
                o.OrderStatus,
                o.Timestamp
            FROM " . TBL_ORDERS . " o
            JOIN " . TBL_USERS . " c ON o.CustomerID = c.userid
            LEFT JOIN " . TBL_USERS . " d ON o.DriverID = d.userid
            ORDER BY
                CASE o.OrderStatus
                    WHEN 'Laukiama' THEN 1
                    WHEN 'Vykdoma' THEN 2
                    WHEN 'Atšaukta' THEN 3
                    WHEN 'Įvykdyta' THEN 4
                    ELSE 5
                END ASC";

    $result = mysqli_query($db, $sql);

    if (!$result || (mysqli_num_rows($result) < 1)) {
        die("Klaida skaitant lentelę 'orders'");
    }

    return $result;
}

function getDriversRated() {
    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    if (!$db) {
        die("Nepavyko prisijungti prie serverio: " . mysqli_connect_error());
    }
    
    $sql = "SELECT
                d.id,
                u.username as DriverName,
                c.Name as CarName,
                d.totalDistanceTraveled,
                d.moneyCollected
            FROM " . TBL_DRIVERS . " d
            JOIN " . TBL_USERS . " u ON d.userid = u.userid
            LEFT JOIN " . TBL_CARS . " c ON d.carID = c.id
            ORDER BY totalDistanceTraveled DESC, moneyCollected DESC";

    $result = mysqli_query($db, $sql);

    if (!$result || (mysqli_num_rows($result) < 1)) {
        die("Klaida skaitant lentelę 'drivers': " . mysqli_error($db));
    }

    return $result;
}
?>