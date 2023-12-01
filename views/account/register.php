<?php
// register.php registracijos forma
// jei pats registruojasi rolė = DEFAULT_LEVEL, jei registruoja ADMIN_LEVEL vartotojas, rolę parenka
// Kaip atsiranda vartotojas: nustatymuose $uregister=
//                                         self - pats registruojasi, admin - tik ADMIN_LEVEL, both - abu atvejai galimi
// formos laukus tikrins procregister.php

session_start();
if (empty($_SESSION['prev'])) { header("Location: logout.php");exit;} // registracija galima kai nera userio arba adminas
// kitaip kai sesija expirinasi blogai, laikykim, kad prev vistik visada nustatoma
include("../../include/config.php");
include("../../include/functions.php");
if ($_SESSION['prev'] != "procregister")  inisession("part");  // pradinis bandymas registruoti

$_SESSION['prev']="register";
?>
    <html>
    <head>
        <title>Taksi sistema</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>
        <body>   
            <div class="container mt-5">
                <div class="d-flex justify-content-center allign-items-center">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header text-center">
                                <h4>Registracija</h4>
                            </div>
                            
                            <div class="card-body">
                                <form action="procregister.php" method="POST" class="login">
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
                                        <div class="row">
                                            <div class="col-3">
                                                <label for="pass">Slaptažodis</label>
                                            </div>
                                        </div>

                                        <input name="pass" id="pass" type="password" class="form-control" value="<?php echo $_SESSION['pass_login']; ?>"/>
                                        <?php echo $_SESSION['pass_error']; ?>
                                    </div>

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
        </body>
    </html>
   
