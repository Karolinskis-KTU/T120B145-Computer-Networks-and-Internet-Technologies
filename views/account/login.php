<?php 
// login.php - tai prisijungimo forma, index.php puslapio dalis 
// formos reikšmes tikrins proclogin.php. Esant klaidų pakartotinai rodant formą rodomos klaidos
// formos laukų reikšmės ir klaidų pranešimai grįžta per sesijos kintamuosius
// taip pat iš čia išeina priminti slaptažodžio.
// perėjimas į registraciją rodomas jei nustatyta $uregister kad galima pačiam registruotis

if (!isset($_SESSION)) { header("Location: logout.php");exit;}
$_SESSION['prev'] = "login";
include("include/config.php");
?>
		     
<div class="container mt-5">
    <div class="d-flex justify-content-center allign-items-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Prisijungimas</h4>
                </div>
                
                <div class="card-body">
                    <form action="views/account/proclogin.php" method="POST" class="login">
                        <div class="form-group">
                            <label for="user">Vartotojo vardas</label>
                            <input name="user" id="user" type="text" class="form-control" value="<?php echo $_SESSION['name_login']; ?>"/>
                            <?php echo $_SESSION['name_error']; ?>
                        </div>

                        <div class="form-group">
                            <labe
                            <div class="row">
                                <div class="col-3">
                                    <label for="pass">Slaptažodis</label>
                                </div>

                                <div class="col-9 text-end">
                                    <!-- <input type="submit" name="problem" value="Pamiršote slaptažodį?" class="btn-link-style"> -->
                                    <style>
                                        .btn-link-style {
                                            background: none;
                                            border: none;
                                            color: #007bff; /* Link color */
                                            text-decoration: underline;
                                            cursor: pointer;
                                        }
                                    </style>
                                </div>
                            </div>

                            <input name="pass" id="pass" type="password" class="form-control" value="<?php echo $_SESSION['pass_login']; ?>"/>
                            <?php echo $_SESSION['pass_error']; ?>
                            
                        </div>

                        </br>

                        <div class="form-group text-center">
                            <input type="submit" name="login" value="Prisijungti" class="btn btn-primary btn-block mx-auto">
                        </div>
                    </form>
                    <?php if ($uregister != "admin") { ?>
                        </br>
                        <p class="text-center" style="margin-top:revert">Arba</p>

                        <div class="form-group text-center mb-3">
                            <a href="views/account/register.php" class="btn btn-success btn-block mx-auto">Registruotis</a>
                            </br>
                        </div>   
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


