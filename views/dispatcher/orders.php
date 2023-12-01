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
            <h3>Visi Užsakymai</h3>
            <?php 
            $orders = getOrders();
            if ($orders) { ?>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Kliento ID</th>
                        <th scope="col">Vairuotojo ID</th>
                        <th scope="col">Paėmimo adresas</th>
                        <th scope="col">Tikslo adresas</th>
                        <th scope="col">Paėmimo koordinatės</th>
                        <th scope="col">Tikslo koordinatės</th>                    
                        <th scope="col">Statusas</th>
                        <th scope="col">Užsakymo pradžios laikas</th>
                        <th scope="col">Veiksmas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_assoc($orders)) {
                            $id=$row['id'];
                            $CustomerID=$row['CustomerName'];
                            $DriverID=$row['DriverName'];
                            $PickupAddress=$row['PickupAddress'];
                            $DestinationAddress=$row['DestinationAddress'];
                            $PickupCoordinates=$row['PickupCoordinates'];
                            $DestinationCoordinates=$row['DestinationCoordinates'];
                            $OrderStatus=$row['OrderStatus'];
                            $Timestamp=$row['Timestamp'];
                            echo "<tr>";
                                echo "<th scope=\"row\">". $id ."</th>";
                                echo "<td>" . $CustomerID . "</td>";
                                echo "<td>" . $DriverID . "</td>";
                                echo "<td>" . $PickupAddress . "</td>";
                                echo "<td>" . $DestinationAddress . "</td>";
                                echo "<td>" . $PickupCoordinates . "</td>";
                                echo "<td>" . $DestinationCoordinates . "</td>";
                                echo "<td>" . $OrderStatus . "</td>";
                                echo "<td>" . $Timestamp . "</td>";
                                echo "<td>";
                                    echo "<a href=\"editOrder.php?row_id=" . $id . "\">";
                                    echo "<button class=\"btn btn-primary\">Keisti</button>";
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