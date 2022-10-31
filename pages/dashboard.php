<?php
session_start(); // In every single page we should start our session at the top of our code

if (!isset($_SESSION['member_id'])) {
   header("Location: /login.php");
   exit();
}
if (isset($_SESSION['admin_id'])) {
   header("Location: /admin.php");
   exit();
}
$ROOT = $_SERVER['DOCUMENT_ROOT'];

$member_email = $_SESSION['member_email'];
$member_id = $_SESSION['member_id'];

$single_stockvell_id = $_GET["stockvell_id"];


require_once($ROOT . "/vendor/autoload.php");
require_once($ROOT . "/config/lang.php");
require_once($ROOT . '/layouts/header.php');
// Check for session 
require_once($ROOT . "/includes/dashboard.inc.php");
require_once($ROOT . "/config/option-list.php");
// require_once($ROOT . "/utils/input-fields.php");
// require_once($ROOT . "/classes/input.classes.php");

use Utils\ErrorHandler;
use Utils\InputField;
use Utils\PeriodConvert;
use Models\Member\FetchMember;
use Models\Stockvell\FetchStockvell;



$stockvell_control = new FetchStockvell(); // Variables getting from dashboard.php
$stockvell_control->setMember($member_email, $member_id);
$cmr_result = $stockvell_control->getCurrentMember(); // cm = current member result
$psr_result = $stockvell_control->getAllPendingStockvell("PENDING", false, $member_id); // psr = pending search result
$asr_result = $stockvell_control->getAllApprovedStockvellOfAMember($member_id); // asr = approved search result
$csr_result = $stockvell_control->getAllCoseStockvellOfAMember($member_id); // csr = close search result

$member_controler = new FetchMember();

$has_error = false;
$err_msg = null;
$err_handler = new ErrorHandler();
if (isset($_GET["error"])) {
   $has_error = true;
   $err_handler->setCommonErrors($_GET["error"]);
}

$input_field = new InputField();
$with_per = new PeriodConvert();


$dl = __("Detail");
$lr = __('Leader');
$sl = __('Resign Leadership');
$cr = __('Close Request');
$gl = __('Generate Link');
?>



<main class="dashboard">

   <section class="section-1">
      <div class="row w-full flex-column-reverse flex-md-row p-0 m-0">
         <!-- Sidebar menu start  -->
         <div class="col-md-3 bg-secondary text-primary sidebar-menus p-0">
            <ul class="d-flex justify-content-between sidebar-menu-items flex-md-column bg-secondary position-md-sticky sticky-md-bottom sticky-md-top p-0 m-0 w-full">
               <li role="button" data-item="profile" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row active">
                  <img class="mx-md-4 mx-0" src="/public/icons/profile.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("Profile"); ?></p>
               </li>
               <li role="button" data-item="my-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row" aria-current="true">
                  <img class="mx-md-4 mx-0" src="/public/icons/my-pack.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("My Pack"); ?></p>
               </li>
               <li role="button" data-item="pending-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/pending-pack.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("Pending Pack"); ?></p>
               </li>
               <li role="button" data-item="close-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/close-pack.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("Closed Packs"); ?></p>
               </li>
               <li role="button" data-item="add-pack" class="border-bottom menu-item border-primary py-3 bg-transparent d-flex flex-column flex-md-row">
                  <img class="mx-md-4 mx-0" src="/public/icons/add-pack.svg" alt="">
                  <p class="m-0 px-3 menu-item-text"><?= __("Add Pack"); ?></p>
               </li>
            </ul>
         </div>
         <!-- Sidebar menu end  -->

         <!-- sidebar content start  -->
         <div class="col-md-9 sidebar-content">

            <!-- profile content start  -->
            <div class="content my-4 profile-content d-block">
               <div class="signup-caption text-center">
                  <h1 class="h1"><?= __("Update your informations!") ?></h1>
                  <p>You can change any field</p>
               </div>
               <?php if ($has_error) echo $err_handler->displayErrors(); ?>
               <!-- Signup Form start  -->
               <form action="/includes/dashboard.inc.php" method="POST" enctype="multipart/form-data">
                  <div class="row mb-3 mx-0">
                     <?php
                     $fn = __('Firstname*');
                     $sn = __('Surname*');
                     $el = __('Email*');
                     $pw = __('Password*');
                     $cpw = __('Confirm Password*');
                     $cy = __('Country*');
                     $cty = __('City*');
                     $pn = __('Phone*');
                     $gr = __('Select Gender*');
                     $pro = __('Profession*');
                     $ist = __("Interest (Optional comma-separated list)");
                     $gid = __("Government ID*");
                     $src = __('How did you hear about the Stockvell platform? (Optional)');

                     echo $input_field->inputText("firstname", $fn, false, "text", false, $cmr_result->firstname);
                     echo $input_field->inputText("surname", $sn, false, "text", false, $cmr_result->surname);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputText("email", $el, true, "email", false, $cmr_result->email);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputText("password", $pw, false, "password", false);
                     echo $input_field->inputText("password2", $pw, false, "password", false);
                     ?>

                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     // echo $cmr_result->country;
                     $county_proper = trim(explode('(', $cmr_result->country)[0]);
                     echo $input_field->inputSelect("country", $cy, false, $county_proper, $pure_code_of_country);
                     echo $input_field->inputPhone("phone", $pn, false, true, $pure_country_code, $cmr_result->phone);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputText("city", $cty, false, "text", false, $cmr_result->city);
                     echo $input_field->inputSelect("gender", $gr, false, $cmr_result->gender, ["male", "female", "others"]);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputSelect("profession", $pro, false, $cmr_result->profession, $professions);
                     echo $input_field->inputFile("govt_id", $gid, false);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputTextarea("interest", $ist, true, false, $cmr_result->interest);
                     ?>
                  </div>


                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputTextarea("source", $src, true, false, $cmr_result->source);
                     ?>
                  </div>

                  <button type="submit" name="member_update_submit" class="btn btn-primary"><?= __("Update"); ?></button>
               </form>
               <!-- Signup Form end  -->
            </div>
            <!-- profile content end  -->

            <!-- my pack content start  -->
            <div class="content my-pack-content d-none">
               <?php
               if (isset($single_stockvell_id)) {
                  $ssr_result = $stockvell_control->getASingleApprovedStockvell($single_stockvell_id); // ssr = single stockvell result
                  $leader = $member_controler->findMemberByID($ssr_result['leader_id'], '/admin');
                  

                  $stockvell_id_hidden_input = $input_field->inputHidden("stockvell_id", $single_stockvell_id);
               ?>
                  <!-- stockvell detail start  -->
                  <div class="row mx-0 mb-3">
                     <h1 class="h1"><?= $ssr_result['name']; ?></h1>
                     <p><?php echo $ssr_result['description']; ?></p>
                     <p><?= $ssr_result['category']; ?></p>
                     <p> <?= __('Member limit:') . $ssr_result['max_member']; ?></p>
                     <?php if ($leader->id === $member_id) {
                        echo "<div class='d-flex'> 
                                 <p>
                                    $lr $leader->firstname  $leader->surname
                                 </p>
                                 <form action='/includes/admin.inc.php' method='post'>
                                    $stockvell_id_hidden_input
                                    <button type='submit' name='suspend_leader_submit' class='btn btn-danger mx-3'>$sl</button>
                                 </form>
                              </div>";
                     } ?>
                  </div>
                  <!-- stockvell detail end  -->

                  <!-- widthdraw member selection start -->
                  <?php
                  $withdraw_member = null;
                  if ($leader->id === $member_id) {
                  ?>
                     <div class="row mb-3 mx-0">
                        <h3><?= __("Withdraw member"); ?></h3>
                        <form action="/includes/dashboard.inc.php" method="post">
                           <?php echo $stockvell_id_hidden_input;
                           ?>
                           <div class="row mx-0 mb-3">
                              <label for="withdraw_member_id">Select Withdraw Member</label>
                              <select name="withdraw_member_id" id="withdraw_member_id" class="form-control">
                                 <?php
                                 foreach ($ssr_result['members'] as $member) {
                                    // array_push($member_list, $member);
                                    // echo $member['firstname'];
                                    $withdraw_member_id = $member['id'];
                                    $member_name = $member['firstname'] . " " . $member['surname'];
                                    if (intval($ssr_result['withdraw_member_id']) === intval($withdraw_member_id)) {
                                       $withdraw_member = $member;
                                       echo "<option selected value='$withdraw_member_id'>$member_name</option>";
                                    } else {
                                       echo "<option value='$withdraw_member_id'>$member_name</option>";
                                    }
                                 }
                                 ?>
                              </select>
                           </div>
                           <button type="submit" class="btn btn-primary" name="withdraw_member_submit"><?= __('Withdraw member') ?></button>
                        </form>
                        <?php
                        if ($withdraw_member) {
                           // var_dump($withdraw_member);
                           $withdraw_member_name = $withdraw_member["firstname"] . " " . $withdraw_member["surname"];
                           echo "<h3>Withdraw member name: $withdraw_member_name </h3>";
                        }
                        // var_dump($withdraw_member)
                        ?>
                     </div>
                  <?php
                  }
                  ?>
                  <!-- widthdraw member selection end -->

                  <!-- action section start  -->
                  <div class="row mb-3 mx-0">
                     <div class="p alert alert-primary" id="generated-link-display"> <?= $ssr_result['link']; ?> </div>
                     <div class="d-flex justify-content-start">
                        <?php
                        if ($leader->id === $member_id) {
                           $leader_id_hidden_input = $input_field->inputHidden("leader_id", $member_id);
                           echo "
                                 <form action='/includes/dashboard.inc.php' method='post'>
                                    $stockvell_id_hidden_input 
                                    $leader_id_hidden_input 
                                    <button type='submit' class='btn mr-2 btn-danger' name='stockvell_close_request_submit'>$cr</button>
                                 </form>
                                 ";

                           echo "
                                 <form action='/includes/dashboard.inc.php' method='post'>
                                    $stockvell_id_hidden_input 
                                    $leader_id_hidden_input 
                                    <button type='submit' class='btn mx-2 btn-primary' id='generate-link' name='stockvell_generate_link_submit' >$gl</button>
                                 </form>
                                 ";
                        }
                        ?>
                        <a href="/dashboard" class="btn mx-2 btn-primary"><?= __("Back to the list"); ?></a>


                     </div>
                  </div>
                  <!-- action section end  -->
               <?php
               } else {
               ?>
                  <!-- my packlist start  -->
                  <h1 class="h1 text-center"><?= __("All of my pack!") ?></h1>
                  <p class="text-center"><?= __("All the pack that you are a member of.") ?></p>
                  <?php
                  if (count($asr_result) <= 0) {
                     echo "<div class='alert alert-warning'>No pack found</div>";
                  } else { ?>
                     <div class="table-responsive">
                        <table class="table table-bordered border-warning">
                           <thead class="bg-warning text-white border-primary">
                              <tr>
                                 <th scope="col">#<?= __("ID") ?></th>
                                 <th scope="col"><?= __("Name") ?></th>
                                 <th scope="col"><?= __("Goal") ?></th>
                                 <th scope="col"><?= __("Category") ?></th>
                                 <th scope="col"><?= __("Status") ?></th>
                                 <th scope="col"><?= __("Payment") ?></th>
                                 <th scope="col"><?= __("Payment Period") ?></th>
                                 <th scope="col"><?= __("Withdraw Period") ?></th>
                                 <th scope="col"><?= __("Detail") ?></th>
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
                                 <tr class='text-capitalize'>
                                    <th>" . $asr_key["id"] . "</th>
                                    <td>" . $asr_key["name"] . "</td>
                                    <td>" . $asr_key["goal"] . "</td>
                                    <td>" . $asr_key["category"] . "</td>
                                    <td>" . $asr_key["status"] . "</td>
                                    <td>" . $asr_key["payment"] . "</td>
                                    <td>" . $asr_key["payment_frequency"] . "</td>
                                    <td>" . $with_per->convertFromIntToText($asr_key["withdraw_frequency"]) . "</td>
                                    <td><a href='/dashboard/?stockvell_id=" . $asr_key["id"] . "' class='btn btn-warning'>$dl</a></td>
                                 </tr>
                              ";
                                 }
                              }
                              ?>
                           </tbody>
                        </table>
                     </div>
                  <?php }               ?>
                  <!-- my packlist end  -->
               <?php
               }
               ?>
            </div>
            <!-- my pack content end  -->

            <!-- pending content start  -->
            <div class="content pending-pack-content d-none">
               <h1 class="h1"><?= __("All pending packs!") ?></h1>
               <p><?= __("You can find all the stockvell pack that is made and requested for approvals. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper informations!") ?></p>
               <?php
               // psr = pending stockvell result 
               // $psr_result - getting from dashboard.inc.php
               if (count($psr_result) <= 0) {
                  echo "<div class='alert alert-warning'>No pack found</div>";
               } else { ?>
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
                              <th scope="col">Currency</th>
                              <th scope="col">Payment Period</th>
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
                                    <td>" . $psr_key["currency"] . "</td>
                                    <td>" . $psr_key["payment_frequency"] . "</td>
                                    <td>" . $with_per->convertFromIntToText($psr_key["withdraw_frequency"]) . "</td>
                                 </tr>
                              ";
                              }
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
               <?php }                ?>

            </div>
            <!-- pending content end  -->

            <!-- closed content start  -->
            <div class="content close-pack-content d-none">
               <h1 class="h1"><?= __("All close packs!") ?></h1>
               <p><?= __("You can find all the stockvell pack that is close and requested for closing. N.B. If you can not find a pack that you have created, in that case, the pack is been rejected. Try creating another one with proper informations!") ?></p>
               <?php
               // psr = pending stockvell result 
               // $psr_result - getting from dashboard.inc.php
               if (count($csr_result) <= 0) {
                  echo "<div class='alert alert-warning'>No pack found</div>";
               } else { ?>
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
                              <th scope="col">Currency</th>
                              <th scope="col">Payment Period</th>
                              <th scope="col">Withdraw Period</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           // psr = pending stockvell result 
                           // $csr_result - getting from dashboard.inc.php
                           if (count($csr_result) <= 0) {
                              echo "<div class='alert alert-warning'>No pack found</div>";
                           } else {
                              foreach ($csr_result as $psr_key) {
                                 # code...
                                 echo "
                                 <tr class='text-lowercase'>
                                    <th>" . $psr_key["id"] . "</th>
                                    <td>" . $psr_key["name"] . "</td>
                                    <td>" . $psr_key["goal"] . "</td>
                                    <td>" . $psr_key["category"] . "</td>
                                    <td>" . $psr_key["status"] . "</td>
                                    <td>" . $psr_key["payment"] . "</td>
                                    <td>" . $psr_key["currency"] . "</td>
                                    <td>" . $psr_key["payment_frequency"] . "</td>
                                    <td>" . $with_per->convertFromIntToText($psr_key["withdraw_frequency"]) . "</td>
                                 </tr>
                              ";
                              }
                           }
                           ?>
                        </tbody>
                     </table>
                  </div>
               <?php }                ?>

            </div>
            <!-- closed content end  -->

            <!-- add pack content start -->
            <div class="content add-pack-content d-none my-4">
               <div class="signup-caption text-center">
                  <h1 class="h1"><?= __("Create your own Stockvell pack!") ?></h1>
                  <p><?= __("You can create your own stockvell, in order to do that you need to fill in all the input fields and once you create your will request of creating a new pack will be under our review.") ?></p>
               </div>
               <?php if ($has_error) echo $err_handler->displayErrors(); ?>
               <!-- Form start  -->
               <form action="/includes/dashboard.inc.php" method="POST" enctype="multipart/form-data">
                  <div class="row mb-3 mx-0">
                     <?php // echo inputElement('name', 'Name*', false, 'text'); 
                     $nm = __("Name*");
                     $gl = __("Goal*");
                     $pyt = __("Payment*");
                     $ccc = __("Currency*");
                     $pytf = __("Payment Frequency(days)*");
                     $wdf = __("Withdraw Frequency(days)*");
                     $ct = __("Category*");
                     $ap = __("Address Proof (JPG, PNG, PDF)");
                     $dc = __("Description");
                     $gid = __("Govt ID Proof (JPG, PNG, PDF)");
                     $agmt = __("You Must Write Agreement About This Stockvell Pack*");
                     echo $input_field->inputText("name", $nm, false, "text", true);
                     echo $input_field->inputText("goal", $gl, false, "text", true);
                     ?>

                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputText('payment', $pyt, false, 'number', true);
                     echo $input_field->inputSelect('currency', $ccc, false, strtoupper('XOF (CFA)'), $currency_short);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputSelect("payment_frequency", $pytf, false, null, $freq_days);
                     echo $input_field->inputSelect("withdraw_frequency", $wdf, false, 'Weekly', $with_freq);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputSelect("category", $ct, false, null, $category_list);
                     echo $input_field->inputText("max_member", "Total Member Limit", false, 'number', true);
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputTextarea("desc", $dc, true, true)
                     ?>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputTextarea("agreement", $agmt, true, true)
                     ?>
                  </div>

                  <div class="row mx-0">
                     <hr>
                     <p><?= __("You need to submit all proof because once you create a stockvell pack by default you will request to be the leader of the group. We will check your papers, if everything is okay we will accept your pack!") ?></p>
                  </div>
                  <div class="row mb-3 mx-0">
                     <?php
                     echo $input_field->inputFile("address_proof", $ap, false, true);
                     echo $input_field->inputFile("govt_id_proof", $gid, false, true);
                     ?>
                  </div>
                  <?php
                  echo $input_field->inputHidden("member_id", $member_id)
                  ?>
                  <div class="row mb-3 mx-0">
                     <button type="submit" name="create_stockvell_pack" class="btn btn-primary w-fit">Create Stockvell</button>
                  </div>


               </form>
               <!-- Form end  -->
            </div>
            <!-- add pack content end -->

         </div>
         <!-- sidebar content end  -->
      </div>
   </section>
</main>

<?php require_once("./layouts/footer.php"); ?>