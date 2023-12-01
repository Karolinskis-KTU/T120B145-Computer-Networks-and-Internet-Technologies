<?php
session_start();
include("include/functions.php");
?>

<!DOCTYPE html class="h-100">
<head>
    <title>Taksi sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body class="d-flex flex-column h-100">
    <?php
    if (!empty($_SESSION['user']))     //Jei vartotojas prisijungęs, valom logino kintamuosius ir rodom meniu
    {                                  // Sesijoje nustatyti kintamieji su reiksmemis is DB
                                       // $_SESSION['user'],$_SESSION['ulevel'],$_SESSION['userid'],$_SESSION['umail']
		
		inisession("part");   //   pavalom prisijungimo etapo kintamuosius
		$_SESSION['prev']="index"; 
        include($_SERVER['DOCUMENT_ROOT'] . "/views/components/navbar.php");
    ?>
        <main class="flex-shrink-0">
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
            <div class="container text-center">
                <h1 class="cover-heading">Sveiki atvykę į "Taksi firma"!</h1>
                    <p class="lead">Iki pasimatymo "Taksi firma" automobiliu! Ačiū, kad pasirinkote mus.</p>
                    <p class="lead">
                        <!-- low text -->
                    </p>
            </div>

            <?php
                include_once($_SERVER['DOCUMENT_ROOT'] . "/include/functions.php");
                if (checkdb($_SESSION['user'])[1] == null) { // Password is null
                    ?>
                    <div class="container text-center">
                        <h1 class="cover-heading">Prašome sukurti naują slaptažodį</h1>
                        <p class="lead">
                            <form action="views/account/processCreatePassword.php" method="POST">
                                <div class="form-group row mx-auto w-50 justify-content-center">
                                    <label for="password" class="col-sm-2 col-form-label">Slaptažodis</label>
                                    <div class="col-sm-5">
                                        <input type="password" name="password" id="password" class="form-control" placeholder="Įveskite slaptažodį" aria-describedby="helpId" value="">
                                    </div>
                                </div>
                                <div class="w-50 mx-auto">
                                    <button type="submit" name="change-range" class="btn btn-primary mt-3">Pakeisti</button>
                                </div>   
                            </form>
                        </p>
                    </div>
                    <?php
                }
            ?>
        </main>
    <?php
    } else {   		           
        if (!isset($_SESSION['prev'])) {
            inisession("full"); // nustatom sesijos kintamuju pradines reiksmes 
        }             
        else {
            if ($_SESSION['prev'] != "proclogin") {
                inisession("part"); // nustatom pradines reiksmes formoms
            }  
   			// jei ankstesnis puslapis perdavė $_SESSION['message']
            if (!empty($_SESSION['message'])) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php echo $_SESSION['message'] ?>
                </div>
            <?php
            }  
		}
        ?>
        <main class="flex-shrink-0">
        <?php include("views/account/login.php"); ?>
        </main>
    <?php } ?>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/views/components/footer.php"); ?>
</body>
</html>
