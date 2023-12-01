<?php
session_start();
// If user is not logged in or is not a customer, redirect to main page
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
if (!isset($_SESSION['ulevel']) || array_search($_SESSION['ulevel'], $user_roles) !== "Vairuotojas") {
    //$_SESSION['error_message'] = "Norėdami peržiūrėti šį puslapį, turite būti prisijungęs kaip vairuotojas.";
    header("Location: /index.php");
    exit();
}


$_SESSION['prev'] = "driver_main";
$userID = $_SESSION['userid'];
include("driver_functions.php");
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
                <div class="alert alert-info" role="alert">
                    <?php echo $sucessMessage; ?>
                </div>
                <?php
                unset($_SESSION['error_message']);
            }
            ?>
            <?php
            // If user has active order, show status page
            if (hasActiveOrder($userID)) { ?>
            <div class="text-center">
                <h1>Aktyvus užsakymas</h1>
            </div>
            <form action="processOrderChange.php" method="POST">
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
                                    <p>
                                        <strong>Užsakymo statusas:</strong> <span id="status"><?php echo getOrderStatus($userID); ?></span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <p><strong>Kelionės pradžia:</strong> <span id="pickupAddress"><?php echo getOrderPickupAddress($userID); ?></span></p>
                                </div>
                                <div class="mb-3">
                                    <p><strong>Kelionės pabaiga:</strong> <span id="destinationAddress"><?php echo getOrderDestinationAddress($userID); ?></span></p>
                                </div>
                                <div class="mb-3">
                                    <p><strong>Kaina:</strong> <span id="price"><?php echo getOrderPrice($userID); ?>€</span></p>
                                </div>
                                <div class="mb-3">
                                    <button type="button" id="googleMapsButton" class="btn btn-secondary">Naviguoti su Google Maps</button>
                                </div>
                                <hr>
                                <button type="submit" name="end-order" class="btn btn-success">Pabaigti užsakymą</button>
                                <div class="mb-3">
                                    <p><strong>Pastaba:</strong> Patikrinkite ar keleivis sumokėjo už kelionę</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <?php } else { 
            // If user doesn't have an active order, show button to go to make a new order?>
            <div class="text-center my-auto">
                <?php if (carHasEnoughRange($userID)) { ?>
                    <form action="processRangeChange.php" method="POST">
                        <h1>Jūs neturite pakankamai likusio kūro</h1>
                        <h4>Prašome važiuoti prisipilti kūro</h4>
                        <div class="form-group row mx-auto w-50 justify-content-center">
                            <label for="range" class="col-sm-6 col-form-label">Dabartinis atstumas kilometrais</label>
                            <div class="col-sm-2">
                                <input type="text" name="range" id="range" class="form-control" placeholder="Įveskite atstumą" aria-describedby="helpId" value="<?php echo getCarRange($userID); ?>">
                            </div>
                        </div>
                        <div class="w-50 mx-auto">
                            <button type="submit" name="change-range" class="btn btn-primary mt-3">Pakeisti atstumą</button>
                        </div>
                    </form>
                <?php } else if(hasTooMuchMoney($userID)) { ?>
                    <form action="processMoneyChange.php" method="POST">
                        <h1>Jūs turite per daug pinigų</h1>
                        <h4>Prašome nuvykti į dispečerio biurą</h4>
                        <div class="form-group row mx-auto w-50 justify-content-center">
                            <label for="money" class="col-sm-6 col-form-label">Dabartinis turimas kiekis</label>
                            <div class="col-sm-2">
                                <input type="text" name="money" id="money" class="form-control" placeholder="Įveskite kiekį" aria-describedby="helpId" value="<?php echo getDriverCash($userID); ?>">
                            </div>
                        </div>
                        <div class="w-50 mx-auto">
                            <button type="submit" name="change-range" class="btn btn-primary mt-3">Pakeisti</button>
                        </div>                        
                    </form>
                <?php } else if(isCarOnBreak($userID)) { ?>
                        <h1>Jūs neturite aktyvaus užsakymo</h1>
                        <h4>Šiuo metu turite pertrauką</h4>
                        <p><strong>Dabartinis laikas: </strong><?php echo date("Y-m-d H:i"); ?></p>
                        <p><strong>Pertraukos pabaiga: </strong><?php echo getCarBreakEnd($userID); ?></p>
                <?php } else { ?>
                    <h1>Jūs neturite aktyvaus užsakymo</h1>
                    <h4>Laukiama užsakymo</h4>
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Laukiama...</span>
                    </div>
                <?php } 
                } ?>
            </div>
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
    var fromLocation = new google.maps.LatLng(<?php echo getOrderCoordinatesStart($userID);?>); // Example: New York City
    var toLocation = new google.maps.LatLng(<?php echo getOrderCoordinatesEnd($userID);?>); // Example: Los Angeles

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
<!-- Navigate using Gmaps -->
<script>
// Get references to HTML elements
const startAddressElement = document.getElementById("pickupAddress");
const endAddressElement = document.getElementById("destinationAddress");
const googleMapsButton = document.getElementById("googleMapsButton");

// Event listeners for button clicks
googleMapsButton.addEventListener("click", () => {
    navigateWithGoogleMaps(startAddressElement.textContent, endAddressElement.textContent);
});

// Function to open Google Maps with directions
function navigateWithGoogleMaps(startAddress, endAddress) {
    const url = `https://www.google.com/maps/dir/${encodeURIComponent(startAddress)}/${encodeURIComponent(endAddress)}`;
    window.open(url, "_blank");
}
</script>
</html>
