<?php
session_start();
if (isset($_GET['row_id'])) {
    $row_id =  $_GET['row_id'];
} else {
    $errorMessage = "Pateiktas neteisingas užsakymas";
    header("Location: orders.php?error_message=" . urlencode($errorMessage));
    exit;
}
// If user is not logged in or is not dispatch, redirect to main page
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
if (!isset($_SESSION['ulevel']) || array_search($_SESSION['ulevel'], $user_roles) !== "Dispečeris") {
    //$_SESSION['error_message'] = "Norėdami peržiūrėti šį puslapį, turite būti prisijungęs kaip dispečeris.";
    header("Location: /index.php");
    exit();
}

$_SESSION['prev'] = "editOrder";
?>

<!DOCTYPE html class="h-100">
<head>
    <title>Taksi sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column h-100">
    <?php include("../components/navbar.php"); ?>
        <main>
            <div class="container mt-5">
                <div class="card">
                    <div class="card-body">
                        <?php
                        $db_order=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
                        $sql_order= "SELECT * FROM " . TBL_ORDERS . " WHERE id=" . $row_id;
                        $result_order = mysqli_query($db_order, $sql_order);
                        if (!$result_order || (mysqli_num_rows($result_order) < 1)) {
                            $errorMessage = "Klaida skaitant lentelę 'orders', kur id=" . $row_id;
                            header("Location: orders.php?error_message=" . urlencode($errorMessage));
                            exit;
                        } else {
                            $result_row_order = mysqli_fetch_assoc($result_order); 
                            $order_id=$result_row_order['id'];
                            $CustomerID=$result_row_order['CustomerID'];
                            $DriverID=$result_row_order['DriverID'];
                            $PickupAddress=$result_row_order['PickupAddress'];
                            $DestinationAddress=$result_row_order['DestinationAddress'];
                            $PickupCoordinates=$result_row_order['PickupCoordinates'];
                            $DestinationCoordinates=$result_row_order['DestinationCoordinates'];
                            $OrderStatus=$result_row_order['OrderStatus'];
                            list($PickupLat, $PickupLng) = explode(',', $PickupCoordinates);
                        } ?>
                        <div class="row">
                            <!-- Left Column (Map) -->
                            <div class="col-md-6">
                            <script>
                            function initMap() {
                                const map = new google.maps.Map(document.getElementById("map"), {
                                zoom: 11,
                                center: { lat: 54.89853970353576, lng: 23.904217354945732 },
                                });
                                setClientMarkers(map);
                                setCarMarkers(map);
                                
                            }

                            const clients = [
                                    ["<?php echo $CustomerID; ?>", <?php echo $PickupLat; ?>, <?php echo $PickupLng; ?>],
                                ];

                            const cars = [
                                <?php
                                $db_cars=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
                                $sql_cars= "SELECT * FROM " . TBL_CARS . " WHERE status='Laukia'";
                                $result_cars = mysqli_query($db_cars, $sql_cars);
                                if (! $result_cars || (mysqli_num_rows($result_cars) < 1)) {
                                    $errorMessage = "Šiuo metu nėra laisvų vairuotojų";
                                    header("Location: orders.php?error_message=" . urlencode($errorMessage));
                                    exit;
                                }
                                while($car_row = mysqli_fetch_assoc($result_cars)) {
                                    $cars_id = $car_row['id'];
                                    $cars_Name = $car_row['Name'];
                                    $cars_Range_left = $car_row['Range_left'];
                                    $cars_Coordinates = $car_row['Coordinates'];
                                    $cars_status = $car_row['status'];
                                    $cars_Timestamp = $car_row['Timestamp'];
                                    list($cars_Lat, $cars_Lng) = explode(',', $cars_Coordinates);

                                    echo "[\"" 
                                        . $cars_Name . "\"," 
                                        . $cars_Lat . "," 
                                        . $cars_Lng . ","
                                        . $cars_Range_left . ","
                                        . "\"" . $cars_status . "\","
                                        . "],";
                                }
                                ?>
                            ];

                            function setClientMarkers(map) {
                                const image = {
                                    url: "../../images/markers/person-30.png",
                                    size: new google.maps.Size(30, 30),
                                    origin: new google.maps.Point(0, 0),
                                    anchor: new google.maps.Point(0, 15),
                                };

                                for (let i = 0; i < clients.length; i++) {
                                    const client = clients[i];
                                    const marker = new google.maps.Marker({
                                        map: map,
                                        position: { lat: client[1], lng: client[2] },
                                        icon: image,
                                        //shape: shape,
                                        title: client[0],
                                    });

                                    // Create an InfoWindow for each marker
                                    const infowindow = new google.maps.InfoWindow({
                                        content: `
                                        <div><strong>${client[0]}</strong></div>`,
                                    });

                                    // Add a click event listener to the marker to open the InfoWindow
                                    marker.addListener("click", function() {
                                        infowindow.open(map, marker);
                                    });
                                }
                            }

                            function setCarMarkers(map) {
                                const image = {
                                    url: "../../images/markers/taxi-30.png",
                                    size: new google.maps.Size(30, 30),
                                    origin: new google.maps.Point(0, 0),
                                    anchor: new google.maps.Point(0, 15),
                                };

                                for (let i = 0; i < cars.length; i++) {
                                    const car = cars[i];
                                    const marker = new google.maps.Marker({
                                        map: map,
                                        position: { lat: car[1], lng: car[2] },
                                        icon: image,
                                        //shape: shape,
                                        title: car[0],
                                    });

                                    // Create an InfoWindow for each marker
                                    const infowindow = new google.maps.InfoWindow({
                                        content: `
                                        <div><strong>${car[0]}</strong></div>
                                        <div><strong>Likęs atstumas:</strong>${car[3]}</div>
                                        <div><strong>Statusas:</strong>${car[4]}</div>`,
                                    });

                                    // Add a click event listener to the marker to open the InfoWindow
                                    marker.addListener("click", function() {
                                        infowindow.open(map, marker);
                                    });
                                }
                            }

                            window.initMap = initMap;
                            </script>
                                <div id="map" style="height: 400px;"></div>
                                <script 
                                    src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY;?>&callback=initMap&v=weekly"
                                    defer
                                ></script>
                            </div>
                    
                            <!-- Right Column (Form) -->
                            <div class="col-md-6">
                                <form action="processEditOrder.php" method="POST">
                                    <input type="hidden" name="order-id" value="<?php echo $row_id; ?>">
                                    <h4>Užsakymo redagavimas</h4>
                                    <div class="form-group">
                                        <label for="name">Pasirinkite automobilį:</label>
                                        <select class="form-select" name="auto">
                                        <?php
                                        $db_cars=mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
                                        $sql_cars= "SELECT * FROM " . TBL_CARS . " WHERE status='Laukia' AND NOW() > Timestamp";
                                        $result_cars = mysqli_query($db_cars, $sql_cars);
                                        if (! $result_cars || (mysqli_num_rows($result_cars) < 1)) {
                                            $errorMessage = "Šiuo metu nėra laisvų vairuotojų";
                                            header("Location: orders.php?error_message=" . urlencode($errorMessage));
                                            exit;
                                        }
                                        while($car_row = mysqli_fetch_assoc($result_cars)) {
                                            $cars_id = $car_row['id'];
                                            $cars_Name = $car_row['Name'];
                                            $cars_Range_left = $car_row['Range_left'];
                                            $cars_Coordinates = $car_row['Coordinates'];
                                            $cars_status = $car_row['status'];
                                            $cars_Timestamp = $car_row['Timestamp'];
                                            list($cars_Lat, $cars_Lng) = explode(',', $cars_Coordinates);

                                            echo "<option value=\"" . $cars_id . "\">" . $cars_id . "|" . $cars_Name . "</option>";  
                                        }
                                        ?>
                                        </select>   
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Užsakymo statusas:</label>
                                        <?php echo $OrderStatus; ?>
                                        <select class="form-select" name="order-status">
                                        <?php
                                        $yra = false;
                                        foreach($order_statuses as $x=>$x_value) {
                                            echo "<option ";
                                            if ($x == $OrderStatus) {
                                                $yra=true;
                                                echo "selected ";
                                            }
                                            echo "value=\"".$x."\" ";
                                            echo ">".$x."</option>";
                                        }
                                        ?>
                                        </select>
                                    </div>
                                    <!-- Other form fields go here -->
                                    </br>
                                    <button type="submit" class="btn btn-primary">Išsaugoti</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    <?php include($_SERVER['DOCUMENT_ROOT'] ."/views/components/footer.php"); ?>
</body>
</html>