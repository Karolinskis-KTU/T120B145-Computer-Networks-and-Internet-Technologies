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
    <div class="container mt-5">
                <div class="d-flex justify-content-center allign-items-center">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header text-center">
                                <h4>Naujo vartotojo sukurimas</h4>
                            </div>
                            
                            <div class="card-body">
                                <form action="processCreateUser.php" method="POST" class="login">
                                    <div class="form-group">
                                        <label for="user">Vartotojo vardas</label>
                                        <input name="user" id="user" type="text" class="form-control" value="<?php echo $_SESSION['name_login']; ?>"/>
                                        <?php echo $_SESSION['name_error']; ?>
                                    </div>

                                    <div class="form-group">
                                        <label for="user">El. paštas</label>
                                        <input name="email" id="email" type="text" class="form-control" value="<?php echo $_SESSION['mail_login']; ?>"><br>
                                        <?php echo $_SESSION['mail_error']; ?>                                  
                                    </div>

                                    <div class="form-group">
                                        <label for="role">Rolė</label>
                                        <select name="role" id="role" class="form-control">
                                            <?php
                                            foreach ($user_roles as $role) {
                                                $role_text = array_flip($user_roles)[$role];
                                                echo "<option value=\"" . $role . "\">" . $role_text . "</option>";
                                            }
                                            ?>
                                        </select>

                                    </br>

                                    <div class="form-group text-center">
                                        <input type="submit" name="login" value="Registruoti" class="btn btn-primary btn-block mx-auto">
                                    </div>
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