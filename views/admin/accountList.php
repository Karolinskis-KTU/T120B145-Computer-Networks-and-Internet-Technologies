<?php
session_start();

// If user is not logged in or is not a customer, redirect to main page
// Convert ulevel to string

// If user is not logged in or is not a customer, redirect to main page
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
if (!isset($_SESSION['ulevel']) || array_search($_SESSION['ulevel'], $user_roles) !== "Administratorius") {
    $_SESSION['error_message'] = "Norėdami peržiūrėti šį puslapį, turite būti prisijungęs kaip administratorius.";
    header("Location: /index.php");
    exit();
}
include_once("admin_functions.php");
$_SESSION['prev'] = "accountList";
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
            <h3>Visi vartotojai</h3>
            <a href="createUser.php"> 
                <button class="btn btn-primary">Sukurti naują vartotoją</button>
            </a>
            <?php 
            $users = getUsers();
            if ($users) { ?>
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">Vartotojo vardas</th>
                        <th scope="col">El. paštas</th>
                        <th scope="col">Rolė</th>
                        <th scipe="col">Paskutinį kartą prisijungęs</th>
                        <th scope="col">Veiksmai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while($row = mysqli_fetch_assoc($users)) {
                            $userid=$row['userid'];
                            $username=$row['username'];
                            $email=$row['email'];
                            $userlevel=$row['userlevel'];
                            $timestamp=$row['timestamp'];
                            echo "<tr>";
                                echo "<th scope=\"row\">". $username ."</th>";
                                echo "<td>" . $email . "</td>";
                                echo "<td>" . array_flip($user_roles)[$userlevel] . "</td>";
                                echo "<td>" . $timestamp . "</td>";
                                echo "<td>";
                                    if($userlevel == $user_roles[BLOCKED_LEVEL]) {
                                        echo "<a href=\"editUser.php?userid=" . $userid . "&action=unblock" . "\">";
                                        echo "<button class=\"btn btn-primary\">Atblokuoti</button>";
                                    } else {
                                        echo "<a href=\"editUser.php?userid=" . $userid . "&action=block" . "\">";
                                        echo "<button class=\"btn btn-warning\">Blokuoti</button>";
                                    }


                                    echo "<a href=\"editUser.php?userid=" . $userid . "&action=delete" . "\">";
                                    echo "<button class=\"btn btn-danger\">Šalinti</button>";
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