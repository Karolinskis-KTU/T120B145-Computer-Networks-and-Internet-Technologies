<?php
session_start();

// If user is not logged in or is not a customer, redirect to main page
// Convert ulevel to string

// If user is not logged in or is not a customer, redirect to main page
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
if (!isset($_SESSION['ulevel']) || array_search($_SESSION['ulevel'], $user_roles) !== "Dispečeris") {
    $_SESSION['error_message'] = "Norėdami peržiūrėti šį puslapį, turite būti prisijungęs kaip dispečeris.";
    header("Location: /index.php");
    exit();
}
include_once("dispatcher_functions.php");
$_SESSION['prev'] = "orders";
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
        <div class="container">
            <?php
            if (isset($_SESSION['success_message'])) {
                $sucessMessage = urldecode($_SESSION['success_message']);
                ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $sucessMessage; ?>
                </div>
                <?php
            }
            if (isset($_SESSION['error_message'])) {
                $sucessMessage = urldecode($_SESSION['error_message']);
                ?>
                <div class="alert alert-info" role="alert">
                    <?php echo $sucessMessage; ?>
                </div>
                <?php
            }
            if (isset($_GET['error_message'])) {
                $errorMessage = urldecode($_GET['error_message']);
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errorMessage; ?>
                </div>
                <?php
            }
            unset($_SESSION['success_message']);
            unset($_SESSION['error_message']);
            ?>
            <h3>Statistika</h3>
            <?php 
            $orders = getDriversRated();
            if ($orders) { ?>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">Vairuotojo ID</th>
                        <th scope="col">Vairuotojo vardas</th>
                        <th scope="col">Vairuotojo automobilis</th>              
                        <th scope="col">Nuvažiuotas km kiekis</th>
                        <th scope="col">Kiek uždirbo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_assoc($orders)) {
                            $DriverID=$row['id'];
                            $DriverName=$row['DriverName'];
                            $CarName=$row['CarName'];
                            $totalDistanceTraveled = number_format($row['totalDistanceTraveled'], 2);
                            $moneyCollected=$row['moneyCollected'];
                            echo "<tr>";
                                echo "<th scope=\"row\">". $DriverID ."</th>";
                                echo "<td>" . $DriverName . "</td>";
                                echo "<td>" . $CarName . "</td>";
                                echo "<td>" . $totalDistanceTraveled . "</td>";
                                echo "<td>" . $moneyCollected . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </main>
    <?php include($_SERVER['DOCUMENT_ROOT'] ."/views/components/footer.php"); ?>
</body>
</html>