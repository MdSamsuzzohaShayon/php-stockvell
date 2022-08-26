<?php
session_start(); // In every single page we should start our session at the top of our code
$member_email = $_SESSION['member_email'];
if (!isset($member_email)) {
   header("Location: /login.php");
   exit();
}
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . '/layouts/header.php');
// Check for session 
require_once($ROOT . "/includes/dashboard.inc.php");
require_once($ROOT . "/config/option-list.php");


$error = $_GET["error"];
// echo $error;
$errors = [];
switch ($error) {
   case 'stmtfailed': {
         array_push($errors, "Invalid MySQL query!");
      }

   case 'passwordnotmatch': {
         array_push($errors, "Password did not match!");
      }
   default:
      # code...
      break;
}
?>



<main class="dashboard">
   <section class="section-1">
      <div class="row w-full flex-column-reverse flex-md-row p-0 m-0">
         <div class="col-md-3 bg-secondary text-primary sidebar-menus p-0">
            <ul class="d-flex justify-content-between sidebar-menu-items flex-md-column bg-secondary position-md-sticky sticky-md-bottom sticky-md-top p-0 m-0 w-full">
               <li role="button" data-item="profile" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row active">
                  <img class="mx-md-4 mx-0" src="/public/icons/profile.svg" alt="">
                  <p class="m-0 px-3">Profile</p>
               </li>
               <li role="button" data-item="my-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row" aria-current="true">
                  <img class="mx-md-4 mx-0" src="/public/icons/my-pack.svg" alt="">
                  <p class="m-0 px-3">My Pack</p>
               </li>
               <li role="button" data-item="pending-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/pending-pack.svg" alt="">
                  <p class="m-0 px-3">Pending Pack</p>
               </li>
               <li role="button" data-item="add-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/add-pack.svg" alt="">
                  <p class="m-0 px-3">Add Pack</p>
               </li>
            </ul>
         </div>
         <div class="col-md-9">
            <div class="content my-4 profile-content d-block">
               <div class="signup-caption text-center">
                  <h1 class="h1">Update your informations!</h1>
                  <p>You can change any field</p>
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
               <form action="/includes/update-member.inc.php" method="POST">
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" value="<?php echo $result->firstname; ?>" name="firstname" class="form-control text-primary bg-secondary border border-primary" id="firstname">
                     </div>
                     <div class="col-md-6">
                        <label for="surname" class="form-label">Surname</label>
                        <input type="text" value="<?php echo $result->surname; ?>" name="surname" class="form-control text-primary bg-secondary border border-primary" id="surname">
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="one-input">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" value="<?php echo $result->email; ?>" name="email" class="form-control text-primary bg-secondary border border-primary" id="email">
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input name="password" type="password" class="form-control text-primary bg-secondary border border-primary" id="password">
                     </div>
                     <div class="col-md-6">
                        <label for="password2" class="form-label">Confirm Password*</label>
                        <input type="password" name="password2" class="form-control text-primary bg-secondary border border-primary" id="password2">
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <label for="country" class="form-label">Country</label>
                        <select name="country" class="form-control text-primary bg-secondary border border-primary" id="country">
                           <?php
                           foreach ($countries as $country) {
                              if ($country === $result->country) {
                                 echo "<option value='$country' selected class='text-capitalize'> $country </option>";
                              } else {
                                 echo "<option value='$country' class='text-capitalize'> $country </option>";
                              }
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="number" value="<?php echo $result->phone; ?>" name="phone" class="form-control text-primary bg-secondary border border-primary" id="phone">
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <label for="gender" class="form-label">Select Gender*</label>
                        <select name="gender" class="form-control text-primary bg-secondary border border-primary" id="gender">
                           <?php
                           switch ($result->gender) {
                              case "male": {
                                    echo '
                                       <option selected value="male"> Male </option>
                                       <option value="female"> Female </option>
                                       <option value="others"> Others </option>
                                 ';
                                    break;
                                 }
                              case "female": {
                                    echo '
                                       <option value="male"> Male </option>
                                       <option selected value="female"> Female </option>
                                       <option value="others"> Others </option>
                                 ';
                                    break;
                                 }
                              case "others": {
                                    echo '
                                       <option value="male"> Male </option>
                                       <option value="female"> Female </option>
                                       <option selected value="others"> Others </option>
                                 ';
                                    break;
                                 }
                              default: {
                                    echo '
                                       <option selected value="male"> Male </option>
                                       <option value="female"> Female </option>
                                       <option value="others"> Others </option>
                                 ';
                                    break;
                                 }
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-md-6">
                        <label for="profession" class="form-label">Profession</label>
                        <select name="profession" class="form-control text-primary bg-secondary border border-primary" id="profession">
                           <?php
                           foreach ($professions as $profession) {
                              if ($profession === $result->profession) {
                                 echo "<option selected value='$profession'> $profession </option>";
                              } else {
                                 echo "<option value='$profession'> $profession </option>";
                              }
                           }
                           ?>
                        </select>
                     </div>
                  </div>
                  <div class="row mb-3">
                     <div class="col-md-6">
                        <label for="interest" class="form-label">Interest (Optional comma seperated list)</label>
                        <input type="text" value="<?php echo $result->interest; ?>" name="interest" class="form-control text-primary bg-secondary border border-primary" id="interest">
                     </div>
                     <div class="col-md-6">
                        <label for="govt_id" class="form-label">Government ID*</label>
                        <input type="file" value="<?php echo $result->givt_id; ?>" name="govt_id" class="form-control text-primary bg-secondary border border-primary" id="govt_id">
                     </div>
                  </div>


                  <div class="row mb-3">
                     <div class="one-input">
                        <label for="source" class="form-label">How do you hear about the Stockvell platform? (Optional)</label>
                        <textarea rows="2" name="source" class="form-control text-primary bg-secondary border border-primary" id="source"> <?php echo $result->source; ?> </textarea>
                     </div>
                  </div>

                  <button type="submit" name="submit" class="btn btn-primary">Update</button>
               </form>
               <!-- Form end  -->
            </div>
            <div class="content my-pack-content d-none">My Pack content</div>
            <div class="content pending-pack-content d-none">Pending pack content</div>
            <div class="content add-pack-content d-none">Add Pack content</div>
         </div>
      </div>
   </section>
</main>

<?php require_once("./layouts/footer.php"); ?>