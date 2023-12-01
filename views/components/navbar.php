<?php
if (!isset($_SESSION)) {
    header("Location: logout.php");
    exit;
}
include_once($_SERVER['DOCUMENT_ROOT'] . "/include/config.php");
$user=$_SESSION['user'];
$userlevel=$_SESSION['ulevel'];
$role="";
{foreach($user_roles as $x=>$x_value)
    {if ($x_value == $userlevel) $role=$x;}
}
?>

<header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
        <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
        <span class="fs-4">Taksi firma</span>
      </a>

      <ul class="nav nav-pills">
        <?php if ($userlevel == $user_roles[ADMIN_LEVEL]) { // Administratorius ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL . "views/admin/accountList.php"; ?>" class="nav-link">Vartotojai</a>
            </li>
        <?php } if ($userlevel == $user_roles[DISPATCH_LEVEL]) { // Dispecere ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL . "views/dispatcher/orders.php"; ?>" class="nav-link">Užsakymai</a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL . "views/dispatcher/statistics.php"; ?>" class="nav-link">Statistika</a>
            </li>
        <?php } if ($userlevel == 4) { // Vairuotojas ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL . "views/driver/main.php"; ?>" class="nav-link">Mano užsakymai</a>
            </li>
        <?php } if ($userlevel == 2) { // Klientas ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL . "views/customer/main.php"; ?>" class="nav-link">Mano užsakymas</a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL . "views/customer/newOrder.php"; ?>" class="nav-link">Naujas užsakymas</a>
            </li>
        <?php } ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL . "views/message/main.php"; ?>" class="nav-link">Žinutės</a>
            </li>
            <li class="nav-item">
                <a href="<?php echo BASE_URL . "views/account/logout.php"; ?>" class="nav-link">Atsijungti</a>
            </li>

        </ul>
</header>