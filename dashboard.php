<?php
session_start(); // In every single page we should start our session at the top of our code
$member_email = $_SESSION['member_email'];
$member_id = $_SESSION['member_id'];
if (!isset($member_email)) {
   header("Location: /login.php");
   exit();
}
$ROOT = $_SERVER['DOCUMENT_ROOT'];
require_once($ROOT . "/config/lang.php");
require_once($ROOT . '/layouts/header.php');
// Check for session 
require_once($ROOT . "/includes/dashboard.inc.php");
require_once($ROOT . "/config/option-list.php");
require_once($ROOT . "/utils/input-fields.php");
require_once($ROOT . "/classes/input.classes.php");

$error = $_GET["error"];
$err_handler = new ErrorHandler();
$err_arr = $err_handler->setCommonErrors($error);

?>



<main class="dashboard">
   <section class="section-1">
      <div class="row w-full flex-column-reverse flex-md-row p-0 m-0">
         <div class="col-md-3 bg-secondary text-primary sidebar-menus p-0">
            <ul class="d-flex justify-content-between sidebar-menu-items flex-md-column bg-secondary position-md-sticky sticky-md-bottom sticky-md-top p-0 m-0 w-full">
               <li role="button" data-item="profile" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row active">
                  <img class="mx-md-4 mx-0" src="/public/icons/profile.svg" alt="">
                  <p class="m-0 px-3"><?= __("Profile"); ?></p>
               </li>
               <li role="button" data-item="my-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row" aria-current="true">
                  <img class="mx-md-4 mx-0" src="/public/icons/my-pack.svg" alt="">
                  <p class="m-0 px-3"><?= __("My Pack"); ?></p>
               </li>
               <li role="button" data-item="pending-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/pending-pack.svg" alt="">
                  <p class="m-0 px-3"><?= __("Pending Pack"); ?></p>
               </li>
               <li role="button" data-item="add-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/add-pack.svg" alt="">
                  <p class="m-0 px-3"><?= __("Add Pack"); ?></p>
               </li>
            </ul>
         </div>
         <div class="col-md-9 sidebar-content">
            <div class="content my-4 profile-content d-block">
               <div class="signup-caption text-center">
                  <h1 class="h1"><?= __("Update your informations!") ?></h1>
                  <p>You can change any field</p>
               </div>
               <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>
               <!-- Form start  -->
               <form action="/includes/update-member.inc.php" method="POST">
                  <div class="row mb-3">
                     <?php echo inputElement('firstname', 'First Name', false, 'text', $result->firstname); ?>
                     <?php echo inputElement('surname', 'Surname', false, 'text', $result->surname); ?>
                  </div>
                  <div class="row mb-3">
                     <?php echo inputElement('email', 'Email', true, 'email', $result->email); ?>
                  </div>
                  <div class="row mb-3">
                     <?php echo inputElement('password', 'Password', false, 'password', $result->password); ?>
                     <?php echo inputElement('password2', 'Confirm Password', false, 'password', $result->password2); ?>
                  </div>
                  <div class="row mb-3">
                     <?php echo inputElement('country', 'Country', false, 'select', $result->country, null, null, $countries); ?>
                     <?php echo inputElement('phone', 'Phone Number', false, 'text', $result->phone); ?>
                  </div>
                  <div class="row mb-3">
                     <?php echo inputElement('gender', 'Gender', false, 'select', $result->gender, null, "text-capitalize", ["male", "female", "others"]); ?>
                     <?php echo inputElement('profession', 'Profession', false, 'select', $result->profession, null, "text-capitalize", $professions); ?>
                  </div>
                  <div class="row mb-3">
                     <?php echo inputElement('interest', 'Interest (comma seperated list)', false, 'text', $result->interest); ?>
                     <?php echo inputElement('govt_id', 'Government ID', false, 'file', $result->govt_id); ?>
                  </div>


                  <div class="row mb-3">
                     <?php echo inputElement('source', 'How did you hear about the Stockvell platform?', true, 'textarea', $result->source); ?>
                  </div>

                  <button type="submit" name="submit" class="btn btn-primary">Update</button>
               </form>
               <!-- Form end  -->
            </div>
            <div class="content my-pack-content d-none">
               <h1 class="h1"><?= __("All of my pack!") ?></h1>
               <p><?= __("All the pack that you are a member of.") ?></p>
               <div class="table-responsive">
                  <table class="table table-bordered border-warning">
                     <thead class="bg-warning text-white border-primary">
                        <tr>
                           <th scope="col">#<?= __("ID")?></th>
                           <th scope="col"><?= __("Name")?></th>
                           <th scope="col"><?= __("Goal")?></th>
                           <th scope="col"><?= __("Category")?></th>
                           <th scope="col"><?= __("Status")?></th>
                           <th scope="col"><?= __("Payment")?></th>
                           <th scope="col"><?= __("Payment Period")?></th>
                           <th scope="col"><?= __("Leader")?></th>
                           <th scope="col"><?= __("Total Members")?></th>
                           <th scope="col"><?= __("Withdraw Period")?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        // psr = pending stockvell result 
                        // $asr_result - getting from dashboard.inc.php
                        if (count($asr_result) <= 0) {
                           echo "<div class='alert alert-warning'>No pack found</div>";
                        } else {
                           foreach ($asr_result as $asr_key) {
                              # code...
                              echo "
                                 <tr class='text-lowercase'>
                                    <th>" . $asr_key["id"] . "</th>
                                    <td>" . $asr_key["name"] . "</td>
                                    <td>" . $asr_key["goal"] . "</td>
                                    <td>" . $asr_key["category"] . "</td>
                                    <td>" . $asr_key["status"] . "</td>
                                    <td>" . $asr_key["payment"] . "</td>
                                    <td>" . $asr_key["payment_frequency"] . "</td>
                                    <td>Leader</td>
                                    <td>" . $asr_key["totel_members"] . " </td>
                                    <td>" . $asr_key["withdraw_frequency"] . "</td>
                                 </tr>
                              ";
                           }
                        }
                        ?>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="content pending-pack-content d-none">
               Many to many relationship query to list all the member of a stockvell pack
               <h1 class="h1"><?= __("All pending packs!") ?></h1>
               <p><?= __("You can find all the stockvell pack that is made and requested for approvals. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper informations!") ?></p>
               <div class="table-responsive">
                  <table class="table table-bordered border-warning">
                     <thead class="bg-warning text-white border-primary">
                        <tr>
                           <th scope="col">#ID</th>
                           <th scope="col">Name</th>
                           <th scope="col">Goal</th>
                           <th scope="col">Category</th>
                           <th scope="col">Status</th>
                           <th scope="col">Payment</th>
                           <th scope="col">Payment Period</th>
                           <th scope="col">Leader</th>
                           <th scope="col">Total Members (u)</th>
                           <th scope="col">Withdraw Period</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        // psr = pending stockvell result 
                        // $psr_result - getting from dashboard.inc.php
                        if (count($psr_result) <= 0) {
                           echo "<div class='alert alert-warning'>No pack found</div>";
                        } else {
                           foreach ($psr_result as $psr_key) {
                              # code...
                              echo "
                                 <tr class='text-lowercase'>
                                    <th>" . $psr_key["id"] . "</th>
                                    <td>" . $psr_key["name"] . "</td>
                                    <td>" . $psr_key["goal"] . "</td>
                                    <td>" . $psr_key["category"] . "</td>
                                    <td>" . $psr_key["status"] . "</td>
                                    <td>" . $psr_key["payment"] . "</td>
                                    <td>" . $psr_key["payment_frequency"] . "</td>
                                    <td>" . $psr_key["leader_id"] . "</td>
                                    <td> 10 </td>
                                    <td>" . $psr_key["withdraw_frequency"] . "</td>
                                 </tr>
                              ";
                           }
                        }
                        ?>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="content add-pack-content d-none my-4">
               <div class="signup-caption text-center">
                  <h1 class="h1"><?= __("Create your own Stockvell pack!") ?></h1>
                  <p><?= __("You can create your own stockvell, in order to do that you need to fill in all the input fields and once you create your will request of creating a new pack will be under our review.") ?></p>
               </div>
               <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>
               <!-- Form start  -->
               <form action="/includes/dashboard.inc.php" method="POST">
                  <div class="row mb-3">
                     <?php echo inputElement('name', 'Name*', false, 'text'); ?>
                     <?php echo inputElement('goal', 'goal*', false, 'text'); ?>
                  </div>
                  <div class="row mb-3">
                     <?php echo inputElement('payment', 'payment*', false, 'number'); ?>
                     <?php echo inputElement('payment_frequency', 'payment frequency(days)*', false, 'number'); ?>
                  </div>
                  <div class="row mb-3">
                     <?php echo inputElement('category', 'Category*', false, 'select', 'social', null, null, ["social", "professional", "investmant"]); ?>
                     <?php echo inputElement('withdraw_frequency', 'Withdraw frequency(days)*', false, 'number'); ?>
                  </div>
                  <div class="row mb-3">
                     <?php echo inputElement('agreement', 'You muct write agreenment about this stockvell pack*', true, 'textarea', null); ?>
                  </div>


                  <button type="submit" name="create-stockvell" class="btn btn-primary">Create Stockvell</button>
               </form>
               <!-- Form end  -->
            </div>
         </div>
      </div>
   </section>
</main>

<?php require_once("./layouts/footer.php"); ?>