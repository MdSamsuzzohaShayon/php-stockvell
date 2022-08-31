<?php
session_start(); // In every single page we should start our session at the top of our code
$admin_id = $_SESSION['admin_id'];
$ROOT = $_SERVER['DOCUMENT_ROOT'];
$logged_admin = false;
if (isset($admin_id)) $logged_admin = true;

// Check for session 
require_once($ROOT . "/config/lang.php");
require_once($ROOT . "/layouts/header.php");
require_once($ROOT . "/utils/input-fields.php");
require_once($ROOT . "/classes/input.classes.php");
require_once($ROOT . "/includes/admin.inc.php");

$error = $_GET["error"];
$err_handler = new ErrorHandler();
$err_arr = $err_handler->setCommonErrors($error);
?>



<main class="admin">
   <?php
   if ($logged_admin) {
   ?>
      <!-- Authenticated content start  -->
      <section class="section-2">
         <div class="row w-full flex-column-reverse flex-md-row p-0 m-0">
            <div class="col-md-3 bg-secondary text-primary sidebar-menus p-0">
               <ul class="d-flex justify-content-between sidebar-menu-items flex-md-column bg-secondary position-md-sticky sticky-md-bottom sticky-md-top p-0 m-0 w-full">
                  <li role="button" data-item="all-members" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row active">
                     <img class="mx-md-4 mx-0" src="/public/icons/profile.svg" alt="">
                     <p class="m-0 px-3">All members</p>
                  </li>
                  <li role="button" data-item="approved-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row" aria-current="true">
                     <img class="mx-md-4 mx-0" src="/public/icons/my-pack.svg" alt="">
                     <p class="m-0 px-3">Approved Pack</p>
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
            <div class="col-md-9 sidebar-content">
               <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>
               <div class="content my-4 all-members-content d-block">
                  all members (delete from stockvell, add member)
                  <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
               </div>
               <div class="content approved-pack-content d-none">
                  <!-- All stockvells (approved, pending, rejected)
                  See whoever requested to become leader -->
                  <h1 class="h1">All approved packs!</h1>
                  <p>You can find all the stockvell pack that is made and requested for approvals. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper informations!</p>
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
                                    <td>" . $asr_key["leader_id"] . "</td>
                                    <td> 10 </td>
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
                  <h1 class="h1">All pending packs!</h1>
                  <p>You can find all the stockvell pack that is made and requested for approvals. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper informations!</p>
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
                              <th scope="col">Action</th>
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
                                    <td>
                                       <form class='p-0 m-0' action='/includes/admin.inc.php?stockvell_id=" . $psr_key["id"] . "' method='post'>
                                          <button type='submit' name='approve_stockvell' class='btn btn-primary'>Approve</button>  
                                       </form>
                                    </td>
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
                  add or Update stockvell
               </div>
            </div>
         </div>
      </section>
      <!-- Authenticated content end  -->
   <?php
   } else {
   ?>
      <!-- unauthenticated content start  -->
      <div class="section-1">
         <div class="container">
            <div class="admin-login-caption text-center">
               <h1 class="h1">Welcome to stockvell</h1>
               <p>Please enter the followings</p>
            </div>
            <?php if (count($err_arr) > 0) echo $err_handler->displayErrors(); ?>
            <form action="/includes/login.inc.php" method="POST">
               <div class="row mb-3">
                  <?php echo inputElement('email', 'Email', false, 'email'); ?>
                  <?php echo inputElement('password', 'Password', false, 'password'); ?>
               </div>
               <button type="submit" name="login_admin_submit" class="btn btn-primary">login</button>
            </form>
         </div>
      </div>
      <!-- unauthenticated content end  -->
   <?php
   }
   ?>
</main>

<?php require_once("./layouts/footer.php"); ?>