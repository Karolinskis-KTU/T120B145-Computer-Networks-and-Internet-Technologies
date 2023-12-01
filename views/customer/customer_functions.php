<?php
    function hasOneOrder($userID) {
        $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            die("Nepavyko prisijungti prie serverio: " . $db->connect_error);
        }
        $sql =  "SELECT COUNT(*) as orderCount " .
                " FROM orders " .
                " WHERE CustomerID = '" . $userID . "'" .
                " AND OrderStatus IN ('Laukiama', 'Vykdoma')";
        $result = $db->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            $orderCount = $row['orderCount'];
            $hasActiveOrder = ($orderCount > 0);

            $result->free_result();
        } else {
            echo "Klaida: " . $db->error;
        }
        
        return $hasActiveOrder;
    }

    function getActiveOrder($userID) {
        $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            die("Nepavyko prisijungti prie serverio: " . $db->connect_error);
        }
        $sql = "SELECT * FROM orders  
                WHERE CustomerID = '$userID' 
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
            die("Ivyko klaida skaitant duomenų bazę");
            return null;
        }
    }

    function getOrderStatus($userID) {
        $orderData = getActiveOrder($userID);

        if ($orderData) {
            return $orderData['OrderStatus'];
        }
        return null;
    }

    function getCoordinatesStart($userID) {
        $orderData = getActiveOrder($userID);
        return $orderData['PickupCoordinates'];
    }

    function getCoordinatesEnd($userID) {
        $orderData = getActiveOrder($userID);
        return $orderData['DestinationCoordinates'];
    }

    function getStartAdress($userID) {
        $orderData = getActiveOrder($userID);
        return $orderData['PickupAddress'];    
    }

    function getEndAdress($userID) {
        $orderData = getActiveOrder($userID);
        return $orderData['DestinationAddress'];    
    }

    function getOrderPrice($userID) {
        $orderData = getActiveOrder($userID);
        return $orderData['Price'];
    }

    function getOrderDriverFromUsers($userID) {
        $orderData = getActiveOrder($userID);
        $driverID = $orderData['DriverID'];

        $db=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) {
            die("Nepavyko prisijungti prie serverio: " . $db->connect_error);
        }
        $sql = "SELECT * FROM users
                WHERE userid = '$driverID'
                LIMIT 1";
        $result = $db->query($sql);

        if($result) {
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row;
            } else {
                // No active orders found
                die("Nepavyko rasti vairuotojo");
                return null;
            }
            $result->free_result();
        } else {
            // Error occured
            die("Ivyko klaida skaitant duomenų bazę");
            return null;
        }
    }

    function getOrderDriverFromDrivers($userID) {
        $orderDriver = getOrderDriverFromUsers($userID);
        $userID = $orderDriver['userid'];

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
                // No active orders found
                die("Nepavyko rasti vairuotojo");
                return null;
            }
            $result->free_result();
        } else {
            // Error occured
            die("Ivyko klaida skaitant duomenų bazę");
            return null;
        }
    }

    function getOrderDriverName($userID) {
        // If order status is "Laukiama", driver hasn't been assigned to the order yet
        $orderStatus = getOrderStatus($userID);
        if ($orderStatus == "Laukiama") {
            return "<div class=\"spinner-border text-primary\" role=\"status\"></div>";
        } else {
            $orderDriver = getOrderDriverFromUsers($userID);
            return $orderDriver['username'];
        }
    }

    function getOrderCar($userID) {
        $orderDriver = getOrderDriverFromDrivers($userID);
        $carID = $orderDriver['carID'];

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
                // No active orders found
                die("Nepavyko rasti automobili");
                return null;
            }
            $result->free_result();
        } else {
            // Error occured
            die("Ivyko klaida skaitant duomenų bazę");
            return null;
        }
    }

    function getOrderCarName($userID) {
        // If order status is "Laukiama", driver hasn't been assigned to the order yet
        $orderStatus = getOrderStatus($userID);
        if ($orderStatus == "Laukiama") {
            return "<div class=\"spinner-border text-primary\" role=\"status\"></div>";
        } else {
            $car = getOrderCar($userID);
            return $car['Name'];
        }
    }
?>