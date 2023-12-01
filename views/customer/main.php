<?php
session_start();
$_SESSION['prev'] = "customer_main";
$userID = $_SESSION['userid'];
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
include_once("customer_functions.php");

// If user is not logged in or is not a customer, redirect to main page
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
if (!isset($_SESSION['ulevel']) || array_search($_SESSION['ulevel'], $user_roles) !== "Klientas") {
    //$_SESSION['error_message'] = "Norėdami peržiūrėti šį puslapį, turite būti prisijungęs kaip klientas.";
    header("Location: /index.php");
    exit();
}
?>

<!DOCTYPE html>
<html class="h-100">
<head>
    <title>Taksi sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column h-100">
    <?php include("../components/navbar.php"); ?>
    <main class="d-flex align-items-center h-100">
        <div class="container">
            <?php
            if (isset($_SESSION['success_message'])) {
                $sucessMessage = urldecode($_SESSION['success_message']);
                ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $sucessMessage; ?>
                </div>
                <?php
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                $sucessMessage = urldecode($_SESSION['error_message']);
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $sucessMessage; ?>
                </div>
                <?php
                unset($_SESSION['error_message']);
            }
            ?>
            <?php
            // If user has active order, show status page
            if (hasOneOrder($userID)) { ?>
            <div class="text-center">
                <h1>Mano užsakymas</h1>
            </div>
            <div class="card my-auto">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Left side (Map) -->
                            <div id="map" style="height: 400px;"></div>  
                        </div>
                        <div class="col-md-6">
                            <!-- Right side (Info) -->
                            <div class="mb-3">
                                <p><strong>Statusas:</strong> <span id="status"><?php echo getOrderStatus($userID); ?></span></p>
                            </div>
                            <div class="mb-3">
                                <p><strong>Kelionės pradžia:</strong> <span id="price"><?php echo getStartAdress($userID); ?></span></p>
                            </div>
                            <div class="mb-3">
                                <p><strong>Kelionės pabaiga:</strong> <span id="price"><?php echo getEndAdress($userID); ?></span></p>
                            </div>
                            <div class="mb-3">
                                <p><strong>Vairuotojas:</strong> <span id="driver"><?php echo getOrderDriverName($userID); ?></span></p>
                            </div>
                            <div class="mb-3">
                                <p><strong>Automobilis:</strong> <span id="car"><?php echo getOrderCarName($userID); ?></span></p>
                            </div>
                            <div class="mb-3">
                                <p><strong>Kaina:</strong> <span id="price"><?php echo getOrderPrice($userID); ?>€</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { 
            // If user doesn't have an active order, show button to go to make a new order?>
            <div class="text-center my-auto">
                <h1>Jūs neturite aktyvaus užsakymo</h1>
                <a href="<?php echo BASE_URL . "views/customer/newOrder.php"; ?>" class="btn btn-primary">Pradėti naują užsakymą</a>
            </div>
            <?php } ?>
        </div>
    </main>
    <?php include($_SERVER['DOCUMENT_ROOT'] ."/views/components/footer.php"); ?>
</body>
<script>
var map;
var directionsService;
var directionsDisplay;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 0, lng: 0 }, // Default map center
        zoom: 10, // Default zoom level
    });

    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setMap(map);

    // Define the "from" and "to" locations as LatLng objects
    var fromLocation = new google.maps.LatLng(<?php echo getCoordinatesStart($userID);?>); // Example: New York City
    var toLocation = new google.maps.LatLng(<?php echo getCoordinatesEnd($userID);?>); // Example: Los Angeles

    // Calculate and display the route from "from" to "to"
    calculateAndDisplayRoute(fromLocation, toLocation);
}

function calculateAndDisplayRoute(from, to) {
    var request = {
        origin: from,
        destination: to,
        travelMode: 'DRIVING'
    };

    directionsService.route(request, function (result, status) {
        if (status == 'OK') {
            directionsDisplay.setDirections(result);
        }
    });
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY;?>&libraries=places&callback=initMap&v=weekly"></script>
</html>
