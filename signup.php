<?php
session_start(); // In every single page we should start our session at the top of our code
$member_email = $_SESSION['member_email'];
if(isset($member_email)){
   header("Location: /dashboard.php");
   exit();
}

$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/config/option-list.php");
$errors = [];
$error = $_GET["error"];
// echo $error;
switch ($error) {
  case 'stmtfailed': {
      array_push($errors, "Invalid MySQL query!");
    }
  case 'emptyinput': {
      array_push($errors, "Make sure to fill all the fields!");
    }
  case 'invalidusername': {
      array_push($errors, "Username should be more than one charecter long!");
    }
  case 'invalidphone': {
      array_push($errors, "Make sure to use a valid phone number!");
    }
  case 'invalidemail': {
      array_push($errors, "Make sure to use a valid email address!");
    }
  case 'passwordnotmatch': {
      array_push($errors, "Password did not match!");
    }
  case 'alreadyexist': {
      array_push($errors, "This email address is already exist!");
    }
  default:
    # code...
    break;
}


?>



<main class="signup">
  <section class="section-1">
    <div class="container">
      <div class="signup-caption text-center">
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
      <!-- Form start  -->
      <form action="/includes/signup.inc.php" method="POST">
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="firstname" class="form-label">First Name*</label>
            <input type="text" required name="firstname" class="form-control text-primary bg-secondary border border-primary" id="firstname">
          </div>
          <div class="col-md-6">
            <label for="surname" class="form-label">Surname*</label>
            <input type="text" required name="surname" class="form-control text-primary bg-secondary border border-primary" id="surname">
          </div>
        </div>
        <div class="row mb-3">
          <div class="one-input">
            <label for="email" class="form-label">Email*</label>
            <input type="email" required name="email" class="form-control text-primary bg-secondary border border-primary" id="email">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="password" class="form-label">Password*</label>
            <input type="password" required name="password" class="form-control text-primary bg-secondary border border-primary" id="password">
          </div>
          <div class="col-md-6">
            <label for="password2" class="form-label">Confirm Password*</label>
            <input type="password" required name="password2" class="form-control text-primary bg-secondary border border-primary" id="password2">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="country" class="form-label">Country*</label>
            <select name="country" class="form-control text-primary bg-secondary border border-primary" id="country">
              <?php
              foreach ($countries as $country) {
                echo "<option value='$country' class='text-capitalize'> $country </option>";
              }
              ?>
            </select>
          </div>
          <div class="col-md-6">
            <label for="phone" class="form-label">Phone*</label>
            <input type="number" required name="phone" class="form-control text-primary bg-secondary border border-primary" id="phone">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="gender" class="form-label">Select Gender*</label>
            <select name="gender" class="form-control text-primary bg-secondary border border-primary" id="gender">
              <option value="male"> Male </option>
              <option value="female"> Female </option>
              <option value="others"> Others </option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="profession" class="form-label">Profession*</label>
            <select name="profession" class="form-control text-primary bg-secondary border border-primary" id="profession">
              <?php
              foreach ($professions as $profession) {
                echo "<option value='$profession'> $profession </option>";
              }
              ?>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="interest" class="form-label">Interest (Optional comma seperated list)</label>
            <input type="text" required name="interest" class="form-control text-primary bg-secondary border border-primary" id="interest">
          </div>
          <div class="col-md-6">
            <label for="govt_id" class="form-label">Government ID*</label>
            <input type="file" required name="govt_id" class="form-control text-primary bg-secondary border border-primary" id="govt_id">
          </div>
        </div>


        <div class="row mb-3">
          <div class="one-input">
            <label for="source" class="form-label">How do you hear about the Stockvell platform? (Optional)</label>
            <textarea rows="2" name="source" class="form-control text-primary bg-secondary border border-primary" id="source"></textarea>
          </div>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Signup</button>
      </form>
      <!-- Form end  -->
    </div>
  </section>
</main>

<?php require_once($ROOT . "/layouts/footer.php"); ?>