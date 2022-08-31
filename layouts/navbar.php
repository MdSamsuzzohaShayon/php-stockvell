<?php
$ROOT = $_SERVER['DOCUMENT_ROOT'];
?>
<div class="container">
  <div class="d-flex flex-wrap justify-content-between py-4 ">
    <a href="/" class="d-flex align-items-center text-decoration-none">
      <span class="fs-4 text-secondary">Stockvell</span>
    </a>
    <div class="logo">
      <a href="/" class="text-decoration-none">Stockvell</a>
    </div>
    <div class="d-block d-md-none" id="mobile-expand-menu-icon">
      <!-- <i class="bi bi-list text-secondary menu-icon"></i> -->
      <img class="toggle-menu menu-open-icon d-block" src="/public/icons/menu-open.svg" alt="">
      <img class="toggle-menu menu-close-icon d-none" src="/public/icons/menu-close.svg" alt="">
    </div>
    <ul class="d-md-flex d-none flex-wrap justify-content-center flex-column flex-md-row align-items-center p-0 m-0">
      <?php
      if (isset($_SESSION['member_id'])) {
      ?>
        <li class="mx-md-3 list-group-item"> <a href="/packs.php" class="nav-link text-secondary"><?= __("Packs"); ?></a> </li>
        <li class="mx-md-3 list-group-item"><a href="/dashboard.php" class="nav-link text-secondary"><?php echo $_SESSION['member_username']; ?></a></li>
        <li class="mx-md-3 list-group-item"> <a href="/includes/logout.inc.php" class="btn btn-outline-danger"><?= __("Logout") ?> </a> </li>
      <?php
      } elseif (isset($_SESSION['admin_id'])) {
      ?>
        <li class="mx-md-3 list-group-item"> <a href="/packs.php" class="nav-link text-secondary"><?= __("Packs"); ?></a> </li>
        <li class="mx-md-3 list-group-item"><a href="/admin.php" class="nav-link text-secondary"><?php echo $_SESSION['admin_username']; ?></a></li>
        <li class="mx-md-3 list-group-item"> <a href="/includes/logout.inc.php" class="btn btn-outline-danger"><?= __("Logout") ?> </a> </li>
      <?php
      } else {
      ?>
        <li class="mx-md-3 list-group-item"> <a href="/index.php#how-it-work" class="nav-link text-secondary"><?= __("How it works?") ?></a> </li>
        <li class="mx-md-3 list-group-item"> <a href="/login.php" class="nav-link text-secondary"><?= __("Login") ?></a> </li>
        <li class="mx-md-3 list-group-item"> <a href="/signup.php" class="btn btn-outline-secondary"><?= __("Signup") ?></a> </li>
      <?php } ?>
      <li class="mx-md-3 list-group-item">
        <div class="dropdown">
          <div class="dropdown-toggle text-secondary" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?= __("Lang") ?>
          </div>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/index.php?lang=fr">Français</a></li>
            <li><a class="dropdown-item" href="/index.php?lang=en">English</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
  <div class="mobile-menu d-none d-md-none" id="mobile-expand-menu">
    <ul class="d-flex flex-column align-items-end">
      <?php
      if (isset($_SESSION['member_id'])) {
      ?>
        <li class="my-2 list-group-item"> <a href="/packs.php" class="nav-link text-secondary"><?= __("Packs") ?></a> </li>
        <li class="my-2 list-group-item"><a href="/dashboard.php" class="nav-link text-secondary"><?php echo $_SESSION['member_username']; ?></a></li>
        <li class="my-2 list-group-item"> <a href="/includes/logout.inc.php" class="btn btn-outline-danger"><?= __("Logout") ?></a> </li>
      <?php
      } elseif (isset($_SESSION['admin_id'])) {
      ?>
        <li class="my-2 list-group-item"> <a href="/packs.php" class="nav-link text-secondary"><?= __("Packs") ?></a> </li>
        <li class="my-2 list-group-item"><a href="/admin.php" class="nav-link text-secondary"><?php echo $_SESSION['admin_username']; ?></a></li>
        <li class="my-2 list-group-item"> <a href="/includes/logout.inc.php" class="btn btn-outline-danger"><?= __("Logout") ?></a> </li>
      <?php
      } else {
      ?>
        <li class="my-2 list-group-item"> <a href="#" class="nav-link text-secondary"><?= __("How it works?") ?></a> </li>
        <li class="my-2 list-group-item"> <a href="/login.php" class="nav-link text-secondary"><?= __("Login") ?></a> </li>
        <li class="my-2 list-group-item"> <a href="/singup.php" class="btn btn-outline-secondary"><?= __("Signup") ?></a> </li>
      <?php } ?>
      <li class="my-2 list-group-item">
        <div class="dropdown">
          <div class="dropdown-toggle text-secondary" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <?= __("Lang") ?>
          </div>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="index.php?lang=fr">Français</a></li>
            <li><a class="dropdown-item" href="index.php?lang=en">English</a></li>
          </ul>
        </div>
      </li>
    </ul>
  </div>
</div>