<?php
    function hasActiveOrder($userID) {
        $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            die("Nepavyko prisijungti prie serverio: " . $db->connect_error);
        }
        $sql =  "SELECT COUNT(*) as orderCount " .
                " FROM orders " .
                " WHERE DriverID = '" . $userID . "'" .
                " AND OrderStatus IN ('Laukiama', 'Vykdoma')";
        $result = $db->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $orderCount = $row['orderCount'];
            $hasActiveOrder = ($orderCount > 0);

            $result->free_result();
        } else {
            echo "Klaida skaitant užsakymų duomenų bazę: " . $db->error;
        }
        
        return $hasActiveOrder;        
    }

    function getActiveOrder($userID) {
        $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            die("Nepavyko prisijungti prie serverio: " . $db->connect_error);
        }
        $sql = "SELECT * FROM orders  
                WHERE DriverID = '$userID' 
                AND OrderStatus IN ('Laukiama', 'Vykdoma')
                LIMIT 1";

        $result = $db->query($sql);

        if($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                // No active orders found
                die("Nepavyko rasti aktyvaus užsakymo");
                return null;
            }
            $result->free_result();
        } else {
            // Error occured
            die("Ivyko klaida skaitant užsakymų duomenų bazę: " . $db->error);
            return null;
        }        
    }

    function carHasEnoughRange($userID) {
        $driverCar = getDriverCar($userID);
        $carRange = $driverCar['Range_left'];

        return (MINIMUM_CAR_RANGE >= $carRange);
    }

    function getOrderStatus($userID) {
        $activeOrder = getActiveOrder($userID);
        return $activeOrder['OrderStatus'];
    }

    function getOrderPickupAddress($userID) {
        $activeOrder = getActiveOrder($userID);
        return $activeOrder['PickupAddress'];
    }

    function getOrderDestinationAddress($userID) {
        $activeOrder = getActiveOrder($userID);
        return $activeOrder['DestinationAddress'];
    }

    function getOrderPrice($userID) {
        $activeOrder = getActiveOrder($userID);
        return $activeOrder['Price'];
    }

    function getOrderCoordinatesStart($userID) {
        $activeOrder = getActiveOrder($userID);
        return $activeOrder['PickupCoordinates'];
    }

    function getOrderCoordinatesEnd($userID) {
        $activeOrder = getActiveOrder($userID);
        return $activeOrder['DestinationCoordinates'];
    }

    function getDriverInfo($userID) {
        $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            die("Nepavyko prisijungti prie serverio: " . $db->connect_error);
        }
        $sql = "SELECT * FROM drivers  
                WHERE userid = '$userID' 
                LIMIT 1";
        $result = $db->query($sql);

        if($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                die("Nepavyko rasti vairuotojo informacijos");
                return null;
            }
            $result->free_result();
        } else {
            // Error occured
            die("Ivyko klaida skaitant vairuotojų duomenų bazę: " . $db->error);
            return null;
        }
    }

    function hasTooMuchMoney($userID) {
        $driverInfo = getDriverInfo($userID);
        $moneyCollected = $driverInfo['moneyCollected'];

        return (MAXIMUM_MONEY_COLLECTED <= $moneyCollected);
    }

    function getDriverCash($userID) {
        $driverInfo = getDriverInfo($userID);
        $moneyCollected = $driverInfo['moneyCollected'];

        return $moneyCollected;
    }

    function getDriverCar($userID) {
        $driverInfo = getDriverInfo($userID);
        $carID = $driverInfo['carID'];

        $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            die("Nepavyko prisijungti prie serverio: " . $db->connect_error);
        }
        $sql = "SELECT * FROM cars  
                WHERE id = '$carID' 
                LIMIT 1";
        $result = $db->query($sql);

        if($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                die("Nepavyko rasti automobilio informacijos");
                return null;
            }
            $result->free_result();
        } else {
            // Error occured
            die("Ivyko klaida skaitant duomenų bazę: " . $db->error);
            return null;
        }
    }

    function getCarRange($userID) {
        $driverCar = getDriverCar($userID);
        $carRange = $driverCar['Range_left'];

        return $carRange;
    }

    // Check if car is on break
    function isCarOnBreak($userID) {
        $driverCar = getDriverCar($userID);
        $carTimestamp = strtotime($driverCar['Timestamp']);
        if ($carTimestamp !== false) {
            // If the current timestamp is more than the car's timestamp
            // The driver is on break
            return (time() < $carTimestamp);
        }
        
        // Handle the case where the timestamp is not valid or missing
        die("Nezinomas dabartinis laikas");
    }


    function getCarBreakEnd($userID) {
        $driverCar = getDriverCar($userID);
        
        if ($driverCar && isset($driverCar['Timestamp'])) {
            $carTimestamp = strtotime($driverCar['Timestamp']);
            if ($carTimestamp !== false) {
                // Hour:Minute
                $breakEnd = date("Y-m-d H:i", $carTimestamp);
                return $breakEnd;
            }
        }
    
        // Handle the case where the timestamp is not valid or missing
        return "Timestamp not available";
    }
     
?>