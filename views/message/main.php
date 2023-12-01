<?php
session_start();
// If user is not logged in or is not a customer, redirect to main page
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");

$_SESSION['prev'] = "message_main";
$userID = $_SESSION['userid'];
include("message_functions.php");
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
        <div class="container">
            <div class="row">
                <!-- Left Panel for Sending Messages -->
                <div class="col-lg-4">
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
                    <h2>Siųsti žinutę</h2>
                    <form action="processNewMessage.php" method="POST">
                        <input type="hidden" name="senderID" value="<?php echo $userID; ?>">
                        <div class="mb-3">
                            <label for="recipient" class="form-label">Gavėjas:</label>
                            <input type="text" name="recipient" class="form-control" id="recipient" placeholder="Įveskite gavėją">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Žinutė:</label>
                            <textarea name="message" class="form-control" id="message" placeholder="Įveskite žinutę"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Siųsti</button>
                    </form>
                </div>

                <!-- Right Panel for Displaying Received Messages -->
                <div class="col-lg-8">
                    <h2>Gautos žinutės</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Siuntėjas</th>
                                <th scope="col">Gavėjas</th>
                                <th scope="col">Žinutė</th>
                                <th scope="col">Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Display received messages here -->
                            <?php
                            $messages = getMessages($userID);
                            // Check if there are any messages
                            if (mysqli_num_rows($messages) == 0) {
                                echo "<tr>";
                                    echo "<td colspan=\"3\">Nėra gautų žinučių</td>";
                                echo "</tr>";
                            } else {
                                while($row = mysqli_fetch_assoc($messages)) {
                                    $senderID = $row['sender'];
                                    $sender = convertToUsername($senderID);
                                    $recipientID = $row['recipient'];
                                    $recipient = convertToUsername($recipientID);
                                    $message = $row['message'];
                                    $timestamp = $row['timestamp'];
                                    echo "<tr>";
                                        echo "<td>" . $sender . "</td>";
                                        echo "<td>" . $recipient . "</td>";
                                        echo "<td>" . $message . "</td>";
                                        echo "<td>" . $timestamp . "</td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php include("../components/footer.php"); ?>
</body>
</html>
