<?php
session_start();
$_SESSION['prev'] = "newOrder";


$userID = $_SESSION['userid'];
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
include_once("customer_functions.php");
if (hasOneOrder($userID)) {
    $_SESSION['error_message'] .= "Jūs jau turite aktyvų užsakymą";
    header("Location: main.php");
    exit;
}

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
    <main>
        <div class="container mt-5">
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
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Left side (Map) -->
                            <div id="map" style="height: 400px;"></div>  
                        </div>
                        <div class="col-md-6">
                            <form action="processNewOrder.php" method="POST">
                                <div class="mb-3">
                                  <label for="from" class="form-label">Kelionės pradžia</label>
                                  <input type="text" name="from" id="from" class="form-control" placeholder="Įveskite adresą" aria-describedby="helpId">
                                </div>
                                <div class="mb-3">
                                  <label for="to" class="form-label">Kelionės tikslas</label>
                                  <input type="text" name="to" id="to" class="form-control" placeholder="Įveskite adresą" aria-describedby="helpId">
                                </div>
                                <div>
                                    <p>Atstumas: <span id="distance"></span></p>
                                    <p>Kaina: <span id="price"></span></p>
                                </div>

                                <input type="hidden" name="distance" id="distance_value">
                                <input type="hidden" name="price" id="price_value">
                                <input type="hidden" name="start_latitude" id="start_latitude">
                                <input type="hidden" name="start_longitude" id="start_longitude">
                                <input type="hidden" name="end_latitude" id="end_latitude">
                                <input type="hidden" name="end_longitude" id="end_longitude">
                                <button type="submit" id="submitButton" class="btn btn-primary" disabled>Užsakyti</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include($_SERVER['DOCUMENT_ROOT'] ."/views/components/footer.php"); ?>
</body>
<script>
var priceForKilometer = <?php echo PRICE_FOR_KILOMETER; ?>;
var map;
var directionsService;
var directionsDisplay;
var inputFrom;
var inputTo;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: 0, lng: 0 }, // Default map center
        zoom: 10, // Default zoom level
    });

    directionsService = new google.maps.DirectionsService();
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsDisplay.setMap(map);

    inputFrom = new google.maps.places.Autocomplete(document.getElementById('from'));
    inputTo = new google.maps.places.Autocomplete(document.getElementById('to'));

    // Listen for changes in the start and destination input
    inputFrom.addListener('place_changed', function() {
        calculateAndDisplayRoute();
        calculateDistance();
    });
    inputTo.addListener('place_changed', function() {
        calculateAndDisplayRoute();
        calculateDistance();
    });
}

function calculateAndDisplayRoute() {
    var start = inputFrom.getPlace();
    var end = inputTo.getPlace();

    if (!start || !end) {
        // Don't calculate route without both start and destination
        return;
    }

    var request = {
        origin: start.geometry.location,
        destination: end.geometry.location,
        travelMode: 'DRIVING'
    };

    directionsService.route(request, function (result, status) {
        if (status == 'OK') {
            directionsDisplay.setDirections(result);
        }
    });
}

function geocodeAddress() {
    var start = inputFrom.getPlace();
    var end = inputTo.getPlace();

    if (!start || !end) {
        // Don't geocode without both start and destination
        return;
    }

    var geocoder = new google.maps.Geocoder();

    geocoder.geocode({ address: start.formatted_address }, function(results, status) {
        if (status === 'OK' && results[0].geometry.location) {
            var startLocation = results[0].geometry.location;
            document.getElementById('start_latitude').value = startLocation.lat();
            document.getElementById('start_longitude').value = startLocation.lng();
        }
    });

    geocoder.geocode({ address: end.formatted_address }, function(results, status) {
        if (status === 'OK' && results[0].geometry.location) {
            var endLocation = results[0].geometry.location;
            document.getElementById('end_latitude').value = endLocation.lat();
            document.getElementById('end_longitude').value = endLocation.lng();
        }
    });
}

function calculateDistance() {
    var start = inputFrom.getPlace();
    var end = inputTo.getPlace();

    // Check if both "from" and "to" fields have text and if distance has been calculated
    if (start && end && start.formatted_address && end.formatted_address) {
        var service = new google.maps.DistanceMatrixService();
        var request = {
            origins: [start.formatted_address],
            destinations: [end.formatted_address],
            travelMode: 'DRIVING'
        };

        service.getDistanceMatrix(request, function(response, status) {
            if (status == 'OK' && response.rows[0].elements[0].status == 'OK') {
                var distance = response.rows[0].elements[0].distance.value / 1000; // Convert to kilometers
                var price = calculatePrice(distance);

                // Display the distance and price to the user (update the DOM accordingly)
                document.getElementById('distance').textContent = distance.toFixed(2);
                document.getElementById('price').textContent = price.toFixed(2) + ' €'; // Display with euro sign

                // Set the numeric distance and price in hidden input fields
                document.getElementById('distance_value').value = distance;
                document.getElementById('price_value').value = price;

                // Call geocodeAdress to update the fields
                geocodeAddress();

                // Enable the submit button
                document.getElementById('submitButton').disabled = false;
            }
        });
    } else {
        // If any condition is not met, disable the submit button
        document.getElementById('submitButton').disabled = true;
    }
}



function calculatePrice(distance) {
    var price = (distance * priceForKilometer).toFixed(2);
    return parseFloat(price); // Convert it back to a floating-point number if needed
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_API_KEY;?>&libraries=places&callback=initMap&v=weekly"></script>
</html>
