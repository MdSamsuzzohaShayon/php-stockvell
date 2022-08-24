<?php
require_once("./layouts/header.php");
require_once("./config/option-list.php");
$errors = [];
$error = $_GET["error"];
// echo $error;
switch ($error) {
  case 'stmtfailed': {
      array_push($errors, "Invalid MySQL query!");
    }
  case 'usernotfound': {
      array_push($errors, "No user with this email address!");
    }
  case 'incorrectpassword': {
      array_push($errors, "Incorrect password!");
    }
  case 'invalidemail': {
      array_push($errors, "Make sure to use a valid email address!");
    }
  default:
    # code...
    break;
}


?>



<main class="login">
  <section class="section-1">
    <div class="container">
      <div class="login-caption text-center">
        <h1 class="h1">Welcome to stockvell</h1>
        <p>Please enter the followings</p>
      </div>

      <?php
      if (count($errors) > 0) {
        echo "<div class='alert alert-danger'>";
        foreach ($errors as $err) {
          echo "<div class='err-msg d-flex align-items-center'>
          <img src='public/icons/error.svg' width='25' alt='error-message' class='error-message mx-3'>
          <p class='m-0'>$err</p>
        </div>";
        }
        echo "</div>";
      }
      ?>
      <!-- Form start here  -->
      <form action="includes/login.inc.php" method="POST">
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="email" class="form-label">Email*</label>
            <input type="email" required name="email" class="form-control text-primary bg-secondary border border-primary" id="email">
          </div>
          <div class="col-md-6">
            <label for="password" class="form-label">Password*</label>
            <input type="password" required name="password" class="form-control text-primary bg-secondary border border-primary" id="password">
          </div>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">login</button>
      </form>
    </div>
  </section>
</main>

<?php require_once("./layouts/footer.php"); ?>