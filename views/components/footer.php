<footer class="footer mt-auto py-3 bg-light">
  <div class="container text-center">
    <?php if (!empty($_SESSION['user'])) { ?>
        <span class="text-muted">Prisijungęs vartotojas: <?php echo $user; ?></span>
        </br>
        <span class="text-muted">Rolė: <?php echo $role; ?></span>
        </br>
    <?php } ?>
    <span> Karolis Paulavičius IFF-1/1</span>
  </div>
</footer>